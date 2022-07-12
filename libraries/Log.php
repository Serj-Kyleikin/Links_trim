<?php

namespace libraries;

class Log {

    public $ds = DIRECTORY_SEPARATOR;

    // Запись логов

    public function write($log, $data) {

        if(gettype($log) == 'integer') {
            foreach($this->codes as $code => $filename) if($code == $log) $log = $filename;
        }

        $file = $_SERVER['DOCUMENT_ROOT'] . $this->ds . 'logs' . $this->ds . $log;
        $date = '*************' . date('[Y-m-d H:i:s] ') . '*************';

        file_put_contents($file, PHP_EOL . $date, FILE_APPEND | LOCK_EX);
        file_put_contents($file, PHP_EOL . $data, FILE_APPEND);
    }
}