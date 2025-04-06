<?php
    function formatToSQLDate($date_str){
        // convert to yyyy-mm-dd format
        return date('Y-m-d', strtotime($date_str));
    }

?>