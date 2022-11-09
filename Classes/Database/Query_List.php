<?php

namespace Classes\Database\Query_List {
    class Query_List
    {
        public static function whereArray($sql, $tableName, array $conditions ,$additional_query='')
        {
            /*
             * conditions example: [
             *      [
             *          'col' => 'column_name',
             *          'value' => 'value_of_column'
             *      ],
             *      [
             *          'operation'=>'AND',
             *          'col' => 'column_name',
             *          'value' => 'value_of_column'
             *      ]
             * ]
             * */

            if(getenv('USER_DATABASE')) {
                $conditions_part = '';

                foreach ($conditions as $conditionProperty) {
                    if ($conditions[0] !== $conditionProperty) {
                        $conditions_part .= ' ' . $conditionProperty['operation'];
                    }
                    $col = $conditionProperty['col'];
                    $value = $conditionProperty['value'];
                    if ($value === 'NULL') {
                        $conditions_part .= "`$col` IS NULL";
                    } else if ($value === '!NULL') {
                        $conditions_part .= "`$col` IS NOT NULL";
                    } else {
                        $conditions_part .= "`$col` = '$value'";
                    }

                }

                if (getenv("DEBUG")) {
                    echo "SELECT * FROM `$tableName` WHERE $conditions_part $additional_query<br>";
                }
                return mysqli_query($sql, "SELECT * FROM `$tableName` WHERE $conditions_part $additional_query");
            }
            return false;
        }

        public static function insert($sql, $tableName, array $values)
        {
            if(getenv('USER_DATABASE')) {
                $tempColumns = '';
                $tempValues = '';
                foreach ($values as $column => $value) {
                    if ($value != 'NULL') {
                        $tempColumns .= "`$column`,";
                        $tempValues .= "'$value',";
                    }
                }

                $tempColumns = substr($tempColumns, 0, -1);
                $tempValues = substr($tempValues, 0, -1);
                if (getenv("DEBUG")) {
                    echo "INSERT INTO `$tableName` ($tempColumns) VALUES ($tempValues)<br>";
                }
                return mysqli_query($sql, "INSERT INTO `$tableName` ($tempColumns) VALUES ($tempValues)");
            }
            return false;
        }

        public static function update($sql, $tableName, $location, $column, $newValue)
        {
            if(getenv('USER_DATABASE')) {
                if (getenv("DEBUG")) {
                    echo "UPDATE `$tableName` SET `$column` = $newValue WHERE $location";
                }
                return mysqli_query($sql, "UPDATE `$tableName` SET `$column` = $newValue WHERE $location");
            }
            return false;
        }

        public static function Delete($sql, $tableName, array $conditions)
        {
            /*
             * conditions example: [
             *      0 => [
             *          'operation'=>'AND', //is not use absolutely
             *          'col' => 'column_name',
             *          'value' => 'value_of_column'
             *      ],
             *      1 => [
             *          'operation'=>'AND',
             *          'col' => 'column_name',
             *          'value' => 'value_of_column'
             *      ]
             * ]
             * */

            if(getenv('USER_DATABASE')) {
                $conditions_part = '';

                foreach ($conditions as $conditionProperty) {
                    if ($conditions[0] !== $conditionProperty) {
                        $conditions_part .= ' ' . $conditionProperty['operation'];
                    }
                    $col = $conditionProperty['col'];
                    $value = $conditionProperty['value'];
                    $conditions_part .= "`$col` = '$value'";

                }


                if (getenv("DEBUG")) {
                    echo "DELETE FROM `$tableName` WHERE $conditions_part";
                }
                return mysqli_query($sql, "DELETE FROM `$tableName` WHERE $conditions_part");
            }
            return false;
        }
    }
}