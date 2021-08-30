<?php
class ControllerExtensionModuleScreenshotNik extends Controller {
	public function index($setting) {
        $this->document->addScript('catalog/view/javascript/html2canvas.min.js');

        if (isset($setting['frequency_save_screenshot'])) {
            if ($setting['frequency_save_screenshot_unit'] == '0') {
                $data['frequency'] = $setting['frequency_save_screenshot'];
            } else {
                $data['frequency'] = $this->convertExpireTimeToSeconds($setting['frequency_save_screenshot'], $setting['frequency_save_screenshot_unit']);
            }

            $data['frequency'] = (int)$data['frequency'] * 1000;

			return $this->load->view('extension/module/screenshot_nik', $data);
		}
	}

    private function convertExpireTimeToSeconds($time, $unitTime) {
        $seconds = 0;
        switch ($unitTime) {
            case '1':
                $seconds = $time * 60;
                break;
            case '2':
                $seconds = $time * (60 * 60);
                break;
            default:
                break;
        }
        return $seconds;
    }

    public function saveScreenshot() {
        $json = array();

        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $upload_dir_path = 'image/screenshot_users/';

        $layouts_list = array(
            ''
        );

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {

            $img = $this->request->post['image'];

            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);


            $file = $upload_dir_path . date('Y-m-d H-i-s') . '.png';

            if( file_put_contents($file, $data, LOCK_EX) ) {
                $done_files[] = realpath( "$file" );

                echo "<pre>";
                print_r($done_files);
                echo "</pre>";

            }


            // WORKING CODE !!!
//            $image = $this->request->post['image'];
//            $name = time();
//            $image = str_replace('data:image/png;base64,', '', $image);
//            $decoded = base64_decode($image);
//            file_put_contents($upload_dir_path . "/" . $name . ".png", $decoded, LOCK_EX);

//            $upload_dir = 'uploads/';
//
//            if( ! is_dir( $upload_dir_path ) ) mkdir( $upload_dir_path, 0777 );
//
//            $files      = $this->request->files; // полученные файлы
//
//            $done_files = array();
//
//            // переместим файлы из временной директории в указанную
//            foreach( $files as $file ) {
//                $files_name = $file['name'];
//
//                foreach ($files_name as $key => $file_name) {
//                    // Получаем расширение файла
//                    $getMime = explode('.', $file_name);
//                    $mime = end($getMime);
//
//                    $randomName = substr(str_shuffle($permitted_chars), 0, 10) . '.' . $mime;
//
//                    if( move_uploaded_file( $file['tmp_name'][$key], "$upload_dir_path/$randomName" ) ) {
//                        $done_files[] = realpath( "$upload_dir_path/$randomName" );
//                    }
//                }
//
//            }
//
//            $json = $done_files ? array('files' => $done_files ) : array('error' => 'Ошибка загрузки файлов.');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}