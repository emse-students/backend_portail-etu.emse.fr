<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use App\Repository\EventRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExcelController extends AbstractController
{
    private $logger;
    private $eventRepositery;

    public function __construct(LoggerInterface $logger, EventRepository $eventRepository)
    {
        $this->logger = $logger;
        $this->eventRepositery = $eventRepository;
    }

    /**
     * @Route("/api/excel/generate/{eventId}", name="generate_excel", methods={"GET"})
     */
    public function index($eventId)
    {



        $event = $this->eventRepositery->find($eventId);
        $now = new \DateTime();
        $name = $this->slugify($event->getName()).'_du_'.$event->getDate()->format('d-m-Y').'_le_'.$now->format('d-m-Y_H\hi').'.xlsx';
        $bookings = $event->getBookings();

        $spreadsheet = new Spreadsheet();
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle("Feuille 1");
        $cols = [];
        $sheet->setCellValue('A1', 'Date d\'inscription');
        $sheet->setCellValue('B1', 'Prénom NOM');
        $sheet->setCellValue('C1', 'Checké ?');
        array_push($cols, ['type' => 'date', 'index' => 1], ['type' => 'name', 'index' => 2], ['type' => 'checked', 'index' => 3]);
        $colIndex = 4;


        if (!is_null($event->getPrice())) {
            array_push($cols, ['type' => 'paid', 'index' => $colIndex]);
            $sheet->setCellValue($this->colName($colIndex).'1', 'Payé ?');
            $colIndex ++;
        }

        foreach ($event->getFormInputs() as $formInput) {
            if ($formInput->getType() === 'text') {
                array_push($cols, ['type' => 'text', 'index' => $colIndex, 'formInput' => $formInput]);
                $sheet->setCellValue($this->colName($colIndex).'1', $formInput->getTitle());
                $colIndex ++;
            } elseif ($formInput->getType() === 'singleOption') {
                array_push($cols, ['type' => 'singleOption', 'index' => $colIndex, 'formInput' => $formInput]);
                $sheet->setCellValue($this->colName($colIndex).'1', $formInput->getTitle());
                $colIndex ++;
            } elseif ($formInput->getType() === 'multipleOptions') {
                foreach ($formInput->getOptions() as $option) {
                    array_push($cols, ['type' => 'multipleOptions', 'index' => $colIndex, 'formInput' => $formInput, 'option' => $option]);
                    $sheet->setCellValue($this->colName($colIndex).'1', $formInput->getTitle().' '.$option->getValue());
                    $colIndex ++;
                }
            }
        }
//        $sheet->setCellValue('D1', $name);

        $index = 2;
        foreach ($bookings as $booking) {
            $formOutputs = $booking->getFormOutputs();
            foreach ($cols as $col) {
                if ($col['type'] === 'date') {
                    $sheet->setCellValue($this->colName($col['index']).$index, $booking->getCreatedAt());

                } elseif ($col['type'] === 'name') {
                    if (is_null($booking->getUser())) {
                        $sheet->setCellValue($this->colName($col['index']).$index, $booking->getUserName());
                    } else {
                        $sheet->setCellValue($this->colName($col['index']).$index, $booking->getUser()->getFirstname().' '.$booking->getUser()->getLastname());
                    }

                } elseif ($col['type'] === 'checked') {
                    $sheet->setCellValue($this->colName($col['index']).$index, $booking->getChecked());

                } elseif ($col['type'] === 'paid') {
                    $sheet->setCellValue($this->colName($col['index']).$index, $booking->getPaid());

                } elseif ($col['type'] === 'text') {
                    foreach ($formOutputs as $formOutput) {
                        if ($formOutput->getFormInput() == $col['formInput']) {
                            $sheet->setCellValue($this->colName($col['index']).$index, $formOutput->getAnswer());
                        }
                    }

                } elseif ($col['type'] === 'singleOption') {
                    foreach ($formOutputs as $formOutput) {
                        $options = $formOutput->getOptions();
                        if ($formOutput->getFormInput() == $col['formInput'] and count($options) > 0) {
                            $sheet->setCellValue($this->colName($col['index']).$index, $options[0]->getValue());
                        }
                    }

                } elseif ($col['type'] === 'multipleOptions') {
                    foreach ($formOutputs as $formOutput) {
                        $options = $formOutput->getOptions();
                        if ($formOutput->getFormInput() == $col['formInput'] and count($options) > 0) {
                            foreach ($options as $option) {
                                if ($option == $col['option']) {
                                    $sheet->setCellValue($this->colName($col['index']).$index, 1);
                                }
                            }

                        }
                    }
                }
            }
            
            $index ++;
        }

        $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(true);
        /** @var PHPExcel_Cell $cell */
        foreach ($cellIterator as $cell) {
            $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
        }



        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);


        // Create the file
        $writer->save(__DIR__.'/../../public/excel/'.$name);

        $responseData = array(
            "name" => $name
        );
        return new JsonResponse($responseData, Response::HTTP_ACCEPTED);
    }

    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public static function colName($index)
    {
        $colName = ['A', 'B', 'C', 'D', 'E',
        'F', 'G', 'H', 'I', 'J',
        'K', 'L', 'M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T',
        'U', 'V', 'W', 'X', 'Y',
        'Z'];

        $a = $index%26;
        $b = intdiv($index, 26);
        if ($b === 0) {
            return $colName[$a-1];
        } elseif ($a === 0) {
            return $colName[25];
        } else {
            return $colName[$b-1].$colName[$a-1];
        }
    }
}
