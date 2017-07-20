<?php
//  1.上传添加excel 
//接收前台文件， 
function addExcel($pg)
{
    //接收前台文件
    $ex = $_FILES['file_stu'];
    //重设置文件名
    $filename =date ( 'Ymdhis' ).substr($ex['name'], stripos($ex['name'], '.'));
    $path = './excel/'.$filename;//设置移动路径
    move_uploaded_file($ex['tmp_name'], $path);
    //表用函数方法 返回数组
    $exfn=_readExcel($path);
    // redirect('input');
    echo "<pre>";
    print_r($exfn);
    echo "</pre>";
    /*对生成的数组进行数据库的写入*/
    foreach ($exfn as $k => $v) {
        if ($k != 0) {
            $sql = "insert into test1 (name,age) values ('$v[1]',$v[2])";
            $ret=pg_query($pg,$sql) or die('failed');
        }
    }
}
    //创建一个读取excel数据，可用于入库
function _readExcel($path)
{
    //引用PHPexcel 类
    // include_once(IWEB_PATH.'core/util/PHPExcel.php');
    include_once('./PHPExcel/Classes/PHPExcel.php');
    include_once('./PHPExcel/Classes/PHPExcel/IOFactory.php');//静态类
    $type = 'Excel5';//设置为Excel5代表支持2003或以下版本，Excel2007代表2007版
    $xlsReader = PHPExcel_IOFactory::createReader($type);
    $xlsReader->setReadDataOnly(true);
    $xlsReader->setLoadSheetsOnly(true);
    $Sheets = $xlsReader->load($path);
    //开始读取上传到服务器中的Excel文件，返回一个二维数组
    $dataArray = $Sheets->getSheet(0)->toArray();
    return $dataArray;
}
$pg=pg_connect("host='localhost' user='postgres' password='123456' dbname='test'") or die("can't connect to database.");
addExcel($pg);

// // 2，导出下载Excel文件
// // [php] view plain copy
// //  <span style="font-size:24px;">/** 
// //  * 导出文件 
// //  * @author Jef 
// //  * @param 
// //  * @return 
// //  */</span>  
// public function export_file()
// {
//         $u = new IQuery('user');
//         $data = $u->find();
//         $name = 'user_'.time();
//         $this->push($data, $name);
//         $this->redirect('export');
// }  
//      /* 导出excel函数*/
// public function push($data, $name = 'Excel')
// {
//     include_once(IWEB_PATH.'core/util/PHPExcel.php');
//     error_reporting(E_ALL);
//     date_default_timezone_set('Europe/London');
//     $objPHPExcel = new PHPExcel();
//     /*以下是一些设置 ，什么作者  标题啊之类的*/
//     $objPHPExcel->getProperties()->setCreator("转弯的阳光")
//     ->setLastModifiedBy("转弯的阳光")
//     ->setTitle("数据EXCEL导出")
//     ->setSubject("数据EXCEL导出")
//     ->setDescription("备份数据")
//     ->setKeywords("excel")
//     ->setCategory("result file");
//  /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/
//     foreach ($data as $k => $v) {
//          $num=$k+1;
//          $objPHPExcel->setActiveSheetIndex(0)//Excel的第A列，uid是你查出数组的键值，下面以此类推
//                       ->setCellValue('A'.$num, $v['id'])
//                       ->setCellValue('B'.$num, $v['username'])
//                       ->setCellValue('C'.$num, $v['password'])
//                       ->setCellValue('D'.$num, $v['email'])
//                       ->setCellValue('E'.$num, $v['head_ico'])
//                       ->setCellValue('F'.$num, $v['invite'])
//                       ->setCellValue('G'.$num, $v['is_seller_invite']);
//     }
//     $objPHPExcel->getActiveSheet()->setTitle('User');
//     $objPHPExcel->setActiveSheetIndex(0);
//      header('Content-Type: applicationnd.ms-excel');
//      header('Content-Disposition: attachment;filename="'.$name.'.xls"');
//      header('Cache-Control: max-age=0');
//      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//      $objWriter->save('php://output');
//      exit;
// }
?>
