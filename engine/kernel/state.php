<?php

class State extends Socket {

    public static function __callStatic($kin, $lot) {
        if ($state = File::exist(STATE . DS . $kin . '.txt')) {
            return unserialize(file_get_contents($state));
        }
        return parent::__callStatic($kin, $lot);
    }

}