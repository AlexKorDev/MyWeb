<?php
include"../connect/connect.php";
class request{
    private $data;
    private $db;
    private $id_user;
    public function __construct(){
        session_start();
        $this->db=Connect::ConnectionDataBase();
        
        if(isset($_POST['data'])){
            $this->data=json_decode($_POST['data'],true);
            print_r( $this->data);
             print_r($_POST['data']);
            foreach($this->data as $key=>$value){
                $this->data[$key]=strip_tags($this->db->real_escape_string($value));
            }   
        }
        $this->id_user=$_SESSION['user_id'];
    }
    public function connect(){
        session_start();
        $this->db = new mysqli('localhost', 'root', '');
        if ($this->db ->connect_errno) {
        echo "Connect failed:{$this->db ->connect_errno}";
        exit();
        };
        $this->db->select_db("notes");
        if(isset($_POST['data'])){
        $this->data=json_decode($_POST['data'],true);
        foreach($this->data as $key=>$value){
            $this->data[$key]=$this->db->real_escape_string($value);
        }   
        }
        $this->id_user=$_SESSION['user_id'];
    }  
    public function insert(){
        $querySetNote=sprintf("insert into notes (name,header,text,x,y,num,color) value('%s','%s','%s','%d','%d','%d','%s')",$this->data['name'],$this->data['header'],$this->data['text'],$this->data['X'],$this->data['Y'],$this->data['data_num'],$this->data['colorBG']);
        $this->db->query($querySetNote)or die($this->db->error);
        $queryGetIDNote="select id from notes order by id desc limit 1";
        $res=$this->db->query($queryGetIDNote) or die($this->db->error);
        $id_note=$res->fetch_row()[0];
        $querySetRelation=sprintf("insert into relations(id_user,id_note) value('%d','%d')", $this->id_user,$id_note);
        $this->db->query($querySetRelation);
    }
    public function update(){
        $queryGetIdNote=sprintf("select id from notes inner join (SELECT id_note FROM relations WHERE id_user =  '%d') as R on notes.id=R.id_note where num='%d'",$this->id_user,$this->data['data_num']);
        $rez=$this->db->query($queryGetIdNote);
        $id_note=$rez->fetch_row()[0];
        $queryUpdateNote=sprintf("update notes set header='%s', text='%s', color='%s' where id=%d",$this->data['header'],$this->data['text'],$this->data['colorBG'],$id_note);
        $this->db->query($queryUpdateNote);
    }
    public function deleteNote(){
        $queryDeleteNote=sprintf("delete FROM notes WHERE id IN (SELECT id_note FROM relations WHERE id_user ='%d') AND num ='%d'",$this->id_user,$this->data['data_num']);
        $this->db->query($queryDeleteNote);
     
    }
    public function getNotes(){
        $queryGetAll="select name,header,text,num,color from notes where id in(select id_note from relations where id_user='{$this->id_user}')";
        $result=$this->db->query($queryGetAll); 
        $Notes=array();
        while($row=$result->fetch_assoc()){
            $Notes[]=$row;
        }       
        $jsonNotes=json_encode($Notes);
        echo $jsonNotes;   
    }     
}
$r=new request();