<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use function PHPSTORM_META\type;
use PHPUnit\Framework\Constraint\IsTrue;


class HomeController extends Controller
{
    public function downloadSyFile(Request $request){
        $filePath = substr($request['filePath'],58,strlen($request['filePath'])-58);
        $fileName = substr($request['filePath'],65,strlen($request['filePath'])-65);
        return Storage::download($filePath,$fileName);
    }

    public function downloadFile(Request $request){
        $filePath = substr($request['filePath'],58,strlen($request['filePath'])-58);
        return Storage::download($filePath,'SY_Data.csv');
    }

    public function getWelcome()
    {
        if (session()->get('file') == 'exist') {
            if (session()->get("MYFILE_ReNAME") != null && session()->get("MYFILE_NAME") != null) {
                $fileName = session()->get("MYFILE_NAME");
                $fileReName = session()->get("MYFILE_RENAME");
                $filePath = 'E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\aetherupload\\' . $fileReName;
                $file = session()->get('file');
                return view('home\welcome')->with(['filePath' => $filePath, 'fileName' => $fileName,
                                                        'fileReName' => $fileReName, 'file' => $file]);

            } elseif (session()->get('sy_data') != null) {
                $fileRePath = session()->get("sy_data");
                $fileName = 'sy_data.csv';
                $filePath = substr($fileRePath, 83, strlen($fileRePath) - 83);
                $fileReName = substr($fileRePath, 71, strlen($fileRePath) - 71);
                $file = session()->get('file');
                return view('home\welcome')->with(['filePath' => $filePath, 'fileName' => $fileName,
                                                        'fileReName' => $fileReName, 'file' => $file]);
            }
        }
        session()->put('file', 'empty');
        return view('home\welcome')->with(['file' => session()->get('file')]);
    }

    public function postFile(Request $request)
    {
        session()->put('file','exist');
        session()->put('MYFILE_NAME',$request['MyFileName']);
        session()->put('MYFILE_RENAME',$request['MyFileRename']);
        return \redirect(route('getFile'));
    }

    public function getFile(){
        ini_set('memory_limit','500M');
        set_time_limit(0);
        $externName = session()->get('MYFILE_RENAME');
        $getFileName = 'E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\aetherupload\\'.$externName;
        $splFile = new \SplFileObject($getFileName,'r');
        $num = 20;
        $splFile->seek(filesize($getFileName));
        $length = $splFile->key()-1;
        $splFile->seek(0);
        $fileArray = [];
        while ($num >= 0){
            array_push($fileArray,$splFile->current());
            $splFile->next();
            $num--;
        }
        return view('home\getFileDetail')->with(['fileArray'=>$fileArray, 'fileName'=>$getFileName,'len'=>$length]);
    }

    public function cleanData(Request $request){
        set_time_limit(0);
        session()->put('sy_data',$request['filePath']);

        if(is_file('E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_temp_data.csv')){
            $array = true;
        }else{
            $str = "python E:\Programs\NeuPoiExtraction\NeuPoiExtraction\public\Python\Start.py"
        .' '.$request['filePath'].' '.'E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_temp_data.csv'.'"';
            $array = shell_exec("$str");
        }
        $filePath =  $request['filePath'];
        $getFileName = 'E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_temp_data.csv';
        if($array){
            ini_set('memory_limit','500M');
            $splFile = new \SplFileObject($getFileName,'r');
            $num = 20;
            $splFile->seek(filesize($getFileName));
            $length = $splFile->key()-1;
            $splFile->seek(0);
            $fileArray = [];
            while ($num >= 0){
                array_push($fileArray,$splFile->current());
                $splFile->next();
                $num--;
            }
            session()->put(['fileName'=>$request['fileName'],'_token'=>$request['_token']]);
            return view('home\getCleanData')->with(['filePath'=>$filePath, 'fileArray'=>$fileArray,
                                                         'fileName'=>$getFileName,'len'=>$length]);
        }
        else{
            $filePath = substr($getFileName,58,strlen($getFileName)-58);
            Storage::disk('local')->delete($filePath);
            return \redirect(route('getFile'));
        }

    }

    public function cleanTaxiData(Request $request){
        session()->put('sy_temp_data',$request['fileName']);
        set_time_limit(0);
        $filePath =  session()->get('sy_data');
        $cleanFileName = $request['fileName'];

        if(is_file('E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_taxi_data.csv')){
            $array = true;
        }else{
            $str = "python E:\Programs\NeuPoiExtraction\NeuPoiExtraction\public\Python\dis_taxi.py"
            .' '.$request['fileName'].' '.'E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_taxi_data.csv'.'"';
            $array = shell_exec("$str");
        }

        $getFileName = 'E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_taxi_data.csv';
        if($array){
            ini_set('memory_limit','500M');
            $splFile = new \SplFileObject($getFileName,'r');
            $num = 20;
            $splFile->seek(filesize($getFileName));
            $length = $splFile->key()-1;
            $splFile->seek(0);
            $fileArray = [];
            while ($num >= 0){
                array_push($fileArray,$splFile->current());
                $splFile->next();
                $num--;
            }
            return view('home\getDisTaxiData')->with(['filePath'=>$filePath,'cleanFileName'=>$cleanFileName,
                                                           'fileArray'=>$fileArray,'fileName'=>$getFileName,'len'=>$length]);
        }
        else{
            $filePath = substr($getFileName,58,strlen($getFileName)-58);
            Storage::disk('local')->delete($filePath);
            return \redirect(route('getFile'));
        }

    }

    public function KMeansData(Request $request){
        session()->put('sy_taxi_data',$request['fileName']);
        set_time_limit(0);
        ini_set('memory_limit','500M');

        $syFileData =  session()->get('sy_data');
        $cleanFileName = session()->get('sy_temp_data');
        $disFileName = $request['fileName'];

        if(is_file('E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_means_data.csv')){
            $array = true;
        }else{
            $str = "python E:\Programs\NeuPoiExtraction\NeuPoiExtraction\public\Python\MiniBatchKMeans_data.py"
                .' '.$request['fileName'].' '.'E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_means_data.csv'.'"';
            $array = shell_exec("$str");
        }

        $getFileName = 'E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_means_data.csv';
        if($array){
            $splFile = new \SplFileObject($getFileName,'r');
            $num = 20;
            $splFile->seek(filesize($getFileName));
            $length = $splFile->key()-1;
            $splFile->seek(0);
            $fileArray = [];
            $fileMap = [];
            while ($num >= 0){
                array_push($fileArray,$splFile->current());
                $splFile->next();
                $num--;
            }

//            $splFileObj = new \SplFileObject($getFileName,'r');
//            $len = 2000;
//            $splFileObj->seek(0);
//            while ($len >= 0){
//                array_push($fileMap,$splFileObj->current());
//                $splFileObj->next();
//                $len--;
//
            return view('home\getKMeansData')->with(['syFileData'=>$syFileData,'cleanFileName'=>$cleanFileName,
                                                          'fileArray'=>$fileArray,'disFileName'=>$disFileName,
                                                          'fileName'=>$getFileName,'len'=>$length]);
        }
        else{
            $filePath = substr($getFileName,58,strlen($getFileName)-58);
            Storage::disk('local')->delete($filePath);
            return \redirect(route('getFile'));
        }
    }

    public function getBasePoiData(Request $request){
        set_time_limit(0);
        ini_set('memory_limit','500M');
        session()->put('sy_KMeans_data',$request['fileName']);
        $syFileData =  session()->get('sy_data');
        $cleanFileName = session()->get('sy_temp_data');
        $disFileName = session()->get('sy_taxi_data');
        $KMeansFileName = $request['fileName'];

        if(is_file('E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_temp_poi.csv')){
            $array = true;
        }else{
            $str = "python E:\Programs\NeuPoiExtraction\NeuPoiExtraction\public\Python\\temp_poi.py"
                .' '.$request['fileName'].' '.'E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_temp_poi.csv'.'"';
            $array = shell_exec("$str");
        }
        $getFileName = 'E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_temp_poi.csv';
        if($array){

            $splFile = new \SplFileObject($getFileName,'r');
            $num = 20;
            $splFile->seek(filesize($getFileName));
            $length = $splFile->key()-1;
            $splFile->seek(0);
            $fileArray = [];
            while ($num >= 0){
                array_push($fileArray,$splFile->current());
                $splFile->next();
                $num--;
            }
            return view('home\getTempPoiData')->with(['syFileData'=>$syFileData,'cleanFileName'=>$cleanFileName,
                                                           'fileArray'=>$fileArray,'disFileName'=>$disFileName,
                                                           'KMeansFileName'=>$KMeansFileName, 'fileName'=>$getFileName,'len'=>$length]);
        }
        else{
            $filePath = substr($getFileName,58,strlen($getFileName)-58);
            Storage::disk('local')->delete($filePath);
            return \redirect(route('getFile'));
        }
    }

    public function getTestPoiData(Request $request){
        set_time_limit(0);
        ini_set('memory_limit','500M');
        session()->put('sy_temp_poi',$request['fileName']);
        $syFileData =  session()->get('sy_data');
        $cleanFileName = session()->get('sy_temp_data');
        $disFileName = session()->get('sy_taxi_data');
        $KMeansFileName = session()->get('sy_KMeans_data');
        $TempPoiName = $request['fileName'];

        if(is_file('E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_test_poi.csv')){
            $array = true;
        }else{
            $str = "python E:\Programs\NeuPoiExtraction\NeuPoiExtraction\public\Python\get_train_data.py"
            .' '.$request['fileName'].' '.'E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_test_poi.csv'.'"';
            $array = shell_exec("$str");
        }

        $getFileName = 'E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_test_poi.csv';
        if($array){

            $splFile = new \SplFileObject($getFileName,'r');
            $num = 20;
            $splFile->seek(filesize($getFileName));
            $length = $splFile->key()-1;
            $splFile->seek(0);
            $fileArray = [];
            while ($num >= 0){
                array_push($fileArray,$splFile->current());
                $splFile->next();
                $num--;
            }
            return view('home\getTestPoiData')->with(['syFileData'=>$syFileData,'cleanFileName'=>$cleanFileName,
                                                           'fileArray'=>$fileArray,'disFileName'=>$disFileName,
                                                           'KMeansFileName'=>$KMeansFileName, 'TempPoiName'=>$TempPoiName,
                                                           'fileName'=>$getFileName,'len'=>$length]);
        }
        else{
            $filePath = substr($getFileName,58,strlen($getFileName)-58);
            Storage::disk('local')->delete($filePath);
            return \redirect(route('getFile'));
        }
    }

    public function getPoiIdData(Request $request){
        set_time_limit(0);
        ini_set('memory_limit','500M');
        session()->put('sy_test_poi',$request['fileName']);
        $syFileData =  session()->get('sy_data');
        $cleanFileName = session()->get('sy_temp_data');
        $disFileName = session()->get('sy_taxi_data');
        $KMeansFileName = session()->get('sy_KMeans_data');
        $TempPoiName = session()->get('sy_temp_poi');
        $testPoiName = $request['fileName'];

        if(is_file('E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_poi_id.csv')){
            $array = true;
        }else{
            $str = "python E:\Programs\NeuPoiExtraction\NeuPoiExtraction\public\Python\get_poi_id.py"
            .' '.$request['fileName'].' '.'E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_poi_id.csv'.'"';
            $array = shell_exec("$str");
        }

        $getFileName = 'E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_poi_id.csv';
        if($array){
            $splFile = new \SplFileObject($getFileName,'r');
            $splFile->seek(filesize($getFileName));
            $num = $splFile->key()-1;
            $splFile->seek(0);
            $fileArray = [];
            $length = $num;
            while ($num >= 0){
                array_push($fileArray,$splFile->current());
                $splFile->next();
                $num--;
            }
            return view('home\getPoiIdData')->with(['syFileData'=>$syFileData,'cleanFileName'=>$cleanFileName,
                                                           'fileArray'=>$fileArray,'disFileName'=>$disFileName,
                                                           'KMeansFileName'=>$KMeansFileName, 'TempPoiName'=>$TempPoiName,
                                                           'testPoiName'=>$testPoiName,'fileName'=>$getFileName,'len'=>$length]);
        }
        else{
            $filePath = substr($getFileName,58,strlen($getFileName)-58);
            Storage::disk('local')->delete($filePath);
            return \redirect(route('getFile'));
        }
    }

    public function getPoiData(Request $request){
        set_time_limit(0);
        ini_set('memory_limit','4096M');
        session()->put('sy_poi_id',$request['fileName']);
        $syFileData =  session()->get('sy_data');
        $cleanFileName = session()->get('sy_temp_data');
        $disFileName = session()->get('sy_taxi_data');
        $KMeansFileName = session()->get('sy_KMeans_data');
        $TempPoiName = session()->get('sy_temp_poi');
        $testPoiName = session()->get('sy_test_poi');
        $testPoiIdName = $request['fileName'];

        if(is_file('E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_poi.csv')){
            $array = true;
        }else{
            $str = "python E:\Programs\NeuPoiExtraction\NeuPoiExtraction\public\Python\get_poi_classify.py".
                ' '.$testPoiName.' '.$testPoiIdName.' '.'E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_poi_key_word.csv'.
                ' '.$TempPoiName.' '.'E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_poi.csv'.'"';
            $array = shell_exec("$str");
        }
        $getFileName = 'E:\Programs\NeuPoiExtraction\NeuPoiExtraction\storage\app\public\sy_poi.csv';
        if($array){
            $splFile = new \SplFileObject($getFileName,'r');
            $num = 20;
            $splFile->seek(filesize($getFileName));
            $length = $splFile->key()-1;
            $splFile->seek(0);
            $fileArray = [];
            while ($num >= 0){
                array_push($fileArray,$splFile->current());
                $splFile->next();
                $num--;
            }

            return view('home\getSyPoiData')->with(['syFileData'=>$syFileData,'cleanFileName'=>$cleanFileName,
                'fileArray'=>$fileArray,'disFileName'=>$disFileName,
                'KMeansFileName'=>$KMeansFileName, 'TempPoiName'=>$TempPoiName,
                'testPoiName'=>$testPoiName,'testPoiIdName'=>$testPoiIdName,'fileName'=>$getFileName,'len'=>$length]);
        }
        else{
            $filePath = substr($getFileName,58,strlen($getFileName)-58);
            Storage::disk('local')->delete($filePath);
            return \redirect(route('getFile'));
        }
    }

    public function resetSystem(){
        $files = Storage::disk('local')->files('\aetherupload\file\\201805\\');
        Storage::disk('local')->delete($files);
        $files_1 = Storage::disk('local')->files('\public\\');
        Storage::disk('local')->delete($files_1);
        session()->put('file', 'empty');
        return \redirect(route('getWelcome'));
    }
}
