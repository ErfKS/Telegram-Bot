<?php

namespace Classes\Database {

    use Classes\Database\Query_List\Query_List;
    use Classes\TelegramAction;
    use mysqli;

    class DB_Manager
    {
        private static $conn;

        public static function StartConnect(): void
        {
            if(getenv("DEBUG")){
                echo "StartConnect<br>";
            }
            $servername = getenv('DB_SERVER');
            $username = getenv('DB_USERNAME');
            $password = getenv('DB_PASSWORD');
            $dbname = getenv('DB_NAME');

            // Create connection
            self::$conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if (self::$conn->connect_error) {
                $chat_id = constant("chat_id");
                if (getenv('DEBUG')) {
                    $sql = self::$conn;
                    TelegramAction::sendMessage($chat_id, "خطا در برقراری ارتباط با پایگاه داده:\n$sql->connect_error");
                    die("Connection failed: " . $sql->connect_error);
                } else {
                    TelegramAction::sendMessage($chat_id, "درحال حاضر مشکلی در برقرارای ارتباط با پایگاه داده پیش آمده");
                }
            }

            //create user
            if (!self::CheckExistChatId(constant("chat_id"))) {
                self::AddChatId(constant("chat_id"), "first_page");
            }
        }

        public static function CheckExistUser($username,$password): bool{
            $res = Query_List::whereArray(self::$conn,'users',[
                [
                    'col' => 'username',
                    'value' => $username
                ],
                [
                    'operation'=>'AND',
                    'col' => 'password',
                    'value' => $password
                ]
            ]);

            if($res){
                return true;
            }

            return false;
        }

        public static function JustConnect(): void
        {
            if(getenv("DEBUG")){
                echo "StartConnect<br>";
            }
            $servername = getenv('DB_SERVER');
            $username = getenv('DB_USERNAME');
            $password = getenv('DB_PASSWORD');
            $dbname = getenv('DB_NAME');

            // Create connection
            self::$conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if (self::$conn->connect_error) {
                $chat_id = constant("chat_id");
                if (getenv('DEBUG')) {
                    $sql = self::$conn;
                    TelegramAction::sendMessage($chat_id, "خطا در برقراری ارتباط با پایگاه داده:\n$sql->connect_error");
                    die("Connection failed: " . $sql->connect_error);
                } else {
                    TelegramAction::sendMessage($chat_id, "درحال حاضر مشکلی در برقرارای ارتباط با پایگاه داده پیش آمده");
                }
            }
        }

        public static function GetQuestionInfo($call_number){
            $res = Query_List::whereArray(self::$conn,'question calls',[
                [
                    'col' => 'id',
                    'value' => $call_number
                ]
            ]);

            return $res->fetch_assoc();
        }

        public static function CheckExistChatId($chat_id): bool
        {
            if(getenv("DEBUG")){
                echo "CheckExistChatId<br>";
            }

            $res = Query_List::whereArray(self::$conn, 'status', [
                [
                    'col'=>'chat_id',
                    'value'=>$chat_id
                ],
                [
                    'operation'=>'AND',
                    'col' => 'type',
                    'value' => 'user'
                ]
            ]);

            if (!$res)
                return false;

            if (mysqli_num_rows($res) > 0)
                return true;

            return false;
        }

        public static function AddChatId($chat_id, $status): void
        {
            if(getenv("DEBUG")){
                echo "AddChatId<br>";
            }
            Query_List::insert(self::$conn,'status',[
                'chat_id'=>$chat_id,
                'chat_is_bot' => constant('chat_is_bot'),
                'chat_first_name' => constant('chat_first_name'),
                'chat_last_name' => constant('chat_last_name')??'NULL',
                'chat_username' => constant('chat_username'),
                'chat_language_code' => constant('chat_language_code'),
                'type'=>'user',
                'Current_Status'=>$status,
                'date_created'=>date("Y-m-d H:i:s")
            ]);
        }

        public static function UpdateChatStatus($chat_id, $status)
        {
            if(getenv("DEBUG")){
                echo "UpdateChatStatus<br>";
            }
            return Query_List::update(self::$conn, 'status', "`chat_id` = '$chat_id' AND `type` = 'user'", 'Current_Status', "'$status'");
        }

        public static function GetChatStatus($chat_id)
        {
            if(getenv("DEBUG")){
                echo "GetChatStatus<br>";
            }

            $res = Query_List::whereArray(self::$conn, 'status', [
                [
                    'col'=>'chat_id',
                    'value'=>$chat_id
                ],
                [
                    'operation'=>'AND',
                    'col' => 'type',
                    'value' => 'user'
                ]
            ]);

            return $res->fetch_array()['Current_Status']??'first_page';
        }

        public static function CheckIsUserLogged($chat_id): bool
        {
            if(getenv("DEBUG")){
                echo "CheckIsUserLogged<br>";
            }

            $res = Query_List::whereArray(self::$conn, 'status', [
                [
                    'col'=>'chat_id',
                    'value'=>$chat_id
                ],
                [
                    'operation'=>'AND',
                    'col' => 'type',
                    'value' => 'user'
                ]
            ]);

            if ($res) {
                if(!isset($res->fetch_array()['finder']) || $res === false){
                    return false;
                }
                return true;
            }

            return false;
        }

        public static function SaveLoginInfo($chat_id,$phoneNumber,$password): array
        {
            if(getenv("DEBUG")){
                echo "SaveLoginInfo<br>";
            }
            return array(
                'finder' => Query_List::update(self::$conn, 'status', "`chat_id` = '$chat_id'  AND `type` = 'user'", 'finder', "'$phoneNumber'"),
                'password' => Query_List::update(self::$conn, 'status', "`chat_id` = '$chat_id'  AND `type` = 'user'", 'password', "'$password'"),
            );
        }

        public static function GetLoginInfo($chat_id)
        {
            if(getenv("DEBUG")){
                echo "GetLoginInfo<br>";
            }

            $res = Query_List::whereArray(self::$conn, 'status', [
                [
                    'col'=>'chat_id',
                    'value'=>$chat_id
                ],
                [
                    'operation'=>'AND',
                    'col' => 'type',
                    'value' => 'user'
                ]
            ]);

            return $res->fetch_array();
        }

        public static function LogoutUser($chat_id): array
        {
            if(getenv("DEBUG")){
                echo "LogoutUser<br>";
            }
            return array(
                Query_List::update(self::$conn, 'status', "`chat_id` = '$chat_id' AND `type` = 'user'", 'finder', "NULL"),
                Query_List::update(self::$conn, 'status', "`chat_id` = '$chat_id' AND `type` = 'user'", 'password', "NULL")
            );
        }
    }
}