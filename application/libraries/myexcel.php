<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MyExcel{
	private $ins = null;

	public function __construct(){
		require_once './include/phpexcel/PHPExcel.php';

		$objPHPExcel = new PHPExcel();
		$this->ins = $objPHPExcel;
		$objPHPExcel->getProperties()->setCreator('Eddy')
                 ->setLastModifiedBy("Eddy");
	}

	public function getIns(){
		return $this->ins;
	}

	public function exportDataToExcel($file,$data,$title,$download=true) {
		$activeSheet = $this->ins->setActiveSheetIndex(0);

		$startChar = 0x41;
		foreach ($title as $k => $v) {
			$curAsc = $startChar + $k;
			$length = ceil($curAsc / ord('Z'));
			$activeSheet->setCellValue(str_repeat(chr($curAsc), $length) . '1',$v)->getColumnDimension(str_repeat(chr($curAsc), $length))->setAutoSize(true);
		}

		foreach ($data as $k => $v) {
			$startChar = 0x41;
			foreach ($v as $kk => $vv) {
				$curAsc = $startChar + $kk;
				$length = ceil($curAsc / ord('Z'));
				if (preg_match('/^\d+$/', trim($vv)) > 0) {
					$activeSheet->setCellValueExplicit(str_repeat(chr($curAsc), $length) . strval($k + 2),
						$vv,PHPExcel_Cell_DataType::TYPE_STRING);
				} else {
					$activeSheet->setCellValue(str_repeat(chr($curAsc), $length) . strval($k + 2),$vv);
				}
			}
		}

		$objWriter = PHPExcel_IOFactory::createWriter($this->ins, 'Excel5');
		if ($download) {
			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$file.'"');
			header('Cache-Control: max-age=1');
			$objWriter->save('php://output');
		} else {
			$objWriter->save($file);
		}
	}
}