<?php
include $_SERVER['DOCUMENT_ROOT']."/connect/connect.php";
class User{
    private $data_user=array();
    private $db;
    function __construct(){
        session_start();
        $this->db=Connect::ConnectionDataBase();
        $queryCreateUsers = "create table if not exists users(id int unsigned not null auto_increment,name varchar(32),password varchar(60),email varchar(32),primary key(id))";
        $queryCreateNotes="create table if not exists notes(id int(10) unsigned not null auto_increment,name varchar(10),header varchar(255),text varchar(3000),x int,y int,num int unsigned,color varchar(32),date timestamp, primary key(id))";
        $queryCreateRalations="create table if not exists relations(
        id  int(10) unsigned not null auto_increment,
        id_user int(10) unsigned not null,
        id_note int(10) unsigned  not null,
        primary key(id),
        foreign key(id_user) references users(id) on update cascade on delete cascade,
        foreign key(id_note) references notes(id) on update cascade on delete cascade
        )";
        
        $this->db->query($queryCreateUsers);
        $this->db->query($queryCreateNotes);
        $this->db->query($queryCreateRalations);
        
        foreach ($_POST as $key => $value) {
            $this->data_user+=array($key=>htmlspecialchars($this->db->real_escape_string($value)));
        };
        unset($this->data_user['submit']);
        }
    function registr($nameSubmit){
        unset($_SESSION['user_id']);
        unset($_SESSION['errors']);
                
        if(isset($_POST[$nameSubmit])){
            $_SESSION['errors'] = array();
            
            if (!preg_match("/^\w{3,32}$/", $this->data_user['userName'])) {
                $_SESSION['errors'][] = "The NAME must consists of English characters and numbers";
            }

            if (!preg_match("/^\w{3,32}$/", $this->data_user['password'])) {
                $_SESSION['errors'][] = "The PASSWORD must consists of English characters and numbers, and have length of between 3 and 32 ";
            };
            if ($this->data_user['password'] != $this->data_user['password_2']) {
                $_SESSION['errors'][] = "Passwords have not match";
            }
            if (!count($_SESSION['errors'])) {
                $queryCheckName = "select count(*) from users where name=\"{$this->data_user['userName']}\"";
                $result = $this->db->query($queryCheckName)->fetch_row();
                if ($result[0] != 0) {
                    $_SESSION['errors'][] = "The name is already taken";
                } else {
                    $hPassword = password_hash($this->data_user['password'], PASSWORD_DEFAULT);
                    $querySetUser = "insert into users(name, password, email) values(\"{$this->data_user['userName']}\",\"$hPassword\",\"{$this->data_user['email']}\")";
                    $this->db->query($querySetUser);
                    $queryGetID=sprintf("select id from users where name='%s'",$this->data_user['userName']);
                    $_SESSION['user_id']=$this->db->query($queryGetID)->fetch_row()[0];
                    header("Location: http://notes.ru");
                    exit();
                };
            }
        }
    }
    function enter($nameSubmit){
        unset($_SESSION['user_id']);
        unset($_SESSION['errors']);
        if(isset($_POST[$nameSubmit])){
        if(!count($this->db->query("show tables like 'users'")->fetch_row())){
            header('Location:  http://notes.ru/registration/registr.php');
            exit();
        }
        
        $query=sprintf("select password,id from users where name='%s'",$this->data_user['userName']);
        $result=$this->db->query($query);
        $row=$result->fetch_row();
        $hash=$row[0];
        $id=$row[1];
        if(password_verify($this->data_user['password'],$hash)){
            $_SESSION['user_id']=$id;
            header('Location:  http://notes.ru/index.php');
            exit();
        }
        else{
            $_SESSION['errors'][]="Error: Invalid login or password";
        } 
    }
    }
    static function getErrors(){
        if (count($_SESSION['errors'])) {
                foreach ($_SESSION['errors'] as $e) {
                    echo "<p>$e</p>";
                }
            }
    }
    public function checkId(){
        if(!isset($_SESSION['user_id'])){
                header('Location: http://notes.ru/enter/enter.php');
            exit();
        } 
    }
    public function getName(){
        $id=$_SESSION['user_id'];
        $name=$this->db->query("select name from users where id=$id")->fetch_row()[0]; 
        return $name;
    }
}