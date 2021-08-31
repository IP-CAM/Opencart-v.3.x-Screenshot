<?php
class ControllerExtensionModuleScreenshotNik extends Controller {
	public function index($setting) {
        $this->document->addScript('catalog/view/javascript/html2canvas.min.js');

        if (isset($setting['frequency_save_screenshot']) && isset($setting['layout_name'])) {
            if ($setting['frequency_save_screenshot_unit'] == '0') {
                $data['frequency'] = $setting['frequency_save_screenshot'];
            } else {
                $data['frequency'] = $this->convertExpireTimeToSeconds($setting['frequency_save_screenshot'], $setting['frequency_save_screenshot_unit']);
            }

            $data['frequency'] = (int)$data['frequency'] * 1000;

            $data['layout_name'] = $setting['layout_name'];

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
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['image']) && isset($this->request->post['layout_name'])) {
            $ip = $this->request->server['REMOTE_ADDR'];

            $upload_dir_path = 'image/screenshot_users/' . $this->request->post['layout_name'] . '/' . $ip;

            if( ! is_dir( $upload_dir_path ) ) mkdir( $upload_dir_path, 0777 );

            $img = $this->request->post['image'];

            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);

            $file = $upload_dir_path . '/' . date('Y-m-d H-i-s') . '.png';

            if( file_put_contents($file, $data, LOCK_EX) ) {
                $done_files[] = realpath( "$file" );
            }
        }
    }
}