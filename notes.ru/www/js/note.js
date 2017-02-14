class Note{
    static IncreaseCounter(){
        Note._counter++;
    }
    static getCounter(){
      Note._counter;
    }
    static getCoords(elem) { 
    let box = elem.getBoundingClientRect();
    return {
    top: box.top + pageYOffset,
    left: box.left + pageXOffset
    };
    }
    //выравнивание заметок по высоте
    static alignNote(){
        if(Note._arrayNotes.length!=0){
        let space_for_notes=Note._arrayNotes[0].spaceForNote;
        let arrayColumns=new Array(space_for_notes.clientWidth/(Note._width)^0);
        for(let i=0;i<arrayColumns.length;i++){
            arrayColumns[i]=new Column();
        }
        for(let i=0; i<Note._arrayNotes.length;i++)
        {
            if(!Note._arrayNotes[i].isDeleted()){
            let columnWithMinHeight=Column.columnWithMinHeight(arrayColumns);
            let X=arrayColumns.indexOf(columnWithMinHeight)*(Note._width+Note._margin);
            let Y=columnWithMinHeight.Height()+Note._margin*columnWithMinHeight.array.length;
            columnWithMinHeight.setNote(Note._arrayNotes[i]);
            Note._arrayNotes[i].noteDOMElement.style.transform="translate("+X+"px,"+Y+"px)";
            }
        }
        }
}
    static makeNoteFromCreateElement(create_note=document.getElementById("create_note")){
    let note_text=create_note.getElementsByClassName("text_note")[0];
    let header_note=note_text.previousElementSibling;
    let toolbar_note=note_text.nextElementSibling;
    let space_for_notes=document.getElementById("space_for_notes"); 
        
    header_note.style.display="none";
    toolbar_note.style.display="none";  
     if(document.querySelector("#create_note").firstElementChild.innerHTML.length!=0 || document.querySelector("#create_note").children[1].innerHTML.length!=0){
        Note._arrayNotes.unshift(new Note(space_for_notes));   
        Note._arrayNotes[0].fillNote(document.querySelector("#create_note").getElementsByClassName("header_note")[0].innerHTML,document.querySelector("#create_note").getElementsByClassName("text_note")[0].innerHTML);
        header_note.innerHTML="";
        note_text.innerHTML="";
        Note.alignNote();
        insertRequest(Note._arrayNotes[0]);
    }   
    }
    constructor(element_space_for_note){
      this.spaceForNote=element_space_for_note;
      this.name="note_"+Note._counter;
      this.X=Note.getCoords(element_space_for_note).top;
      this.Y=Note.getCoords(element_space_for_note).left;
      let html_text_note="<div class='example header_note' contenteditable='false' data-placeholder='Enter a title'></div><div class='example text_note' contenteditable='false' data-placeholder='Text...'></div><ul class='example note_toolbar' role='toolbar'> <li role='button' class='button_color'>color  <div class='wrp_color'> <div class='choice_color'><div role='button' class='color white' data-color='rgb(255, 255, 255)'></div> <div role='button' class='color grey' data-color='rgb(191, 191, 191)'></div><div role='button' class='color red'  data-color='rgba(229, 115, 115, 0.99)'></div><div role='button' class='color blue' data-color='rgba(138, 142, 255, 0.97)'></div><div role='button' class='color orange' data-color='rgb(222, 121, 20)' ></div><div role='button' class='color yellow' data-color='rgb(229, 237, 42)'></div><div role='button' class='color green'data-color='rgb(43, 212, 69)'></div><div role='button' class='color purple' data-color='rgb(172, 111, 216)'></div> </div></div></li> <li role='button' class='button_delete'>delete</li> <li role='button' class='button_ok'>save</li> </ul> </div>";
      this.header=null;
      this.text=null;
      this.noteDOMElement=document.createElement("div");
      this.noteDOMElement.className="example_note";
      this.data_num=Note._counter;
      this.noteDOMElement.setAttribute("data-num",this.data_num);  
      this.noteDOMElement.innerHTML=html_text_note;
      let choiceColor=this.noteDOMElement.getElementsByClassName("color");
      for(let buttonColor of choiceColor)
          {
              buttonColor.style.backgroundColor=buttonColor.getAttribute("data-color");
          }
      this.colorBG="rgb(255,255,255)";
      Note._counter++;
      this.clickEventBGColor();
      this.clickEventDelete();
      this.clickEventSave();
      this.clickEvent();
      
        }
    //заполнение DOM объекта
    fillNote(header,text){
        this.header=header.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
        this.text=text.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
        this.getElementHeader().innerHTML=this.header;
        this.getElementText().innerHTML=this.text;
        this.spaceForNote.appendChild(this.noteDOMElement);
        
};
    CreateDOMElement(){
        this.getElementHeader().innerHTML=this.header;
        this.getElementText().innerHTML=this.text;       
        document.getElementById("space_for_notes").appendChild(this.noteDOMElement);
    }
    delete(){
        this.noteDOMElement.remove();
         Note.alignNote();
    }
    clickEventBGColor(){
        let self=this;
           this.noteDOMElement.addEventListener("click", function(event){
            let target=event.target;
            if(target.classList.contains("color")){
            this.style.backgroundColor=target.getAttribute("data-color");
            self.colorBG=target.getAttribute("data-color");
            }
        },false);
    }
    clickEventDelete(){
        let self=this;
        function del(){
            let target=event.target;
            if(target.classList.contains("button_delete") && confirm("delete?")){
                this.remove();
                this.getElementsByClassName("header_note")[0].setAttribute("contenteditable","false");
                this.getElementsByClassName("text_note")[0].setAttribute("contenteditable","false");
                this.getElementsByClassName("note_toolbar")[0].style.display="none";
                this.style.zIndex="2";
                document.getElementById("background").style.zIndex=1;
                deleteRequest(self);
                Note.alignNote();
            }
        };
        this.noteDOMElement.addEventListener("click", del,false);
        
    }
    clickEventSave(){
        let self=this;
        this.noteDOMElement.addEventListener("click",function(event){
            if(event.target.classList.contains("button_ok")){
                self.header=self.getElementHeader().innerHTML;
                self.text=self.getElementText().innerHTML;
                updateRequest(self);
                this.getElementsByClassName("header_note")[0].setAttribute("contenteditable","false");
                this.getElementsByClassName("text_note")[0].setAttribute("contenteditable","false");
                this.getElementsByClassName("note_toolbar")[0].style.display="none";
                this.style.zIndex="2";
                document.getElementById("background").style.zIndex=1;
                Note.alignNote();
            }
        },false)
    }
    clickEvent(){
        let self=this;
        this.noteDOMElement.addEventListener("click",function(event){
            if(event.target.parentElement.className=="example_note"){
            this.getElementsByClassName("header_note")[0].setAttribute("contenteditable","true");
            this.getElementsByClassName("text_note")[0].setAttribute("contenteditable","true");
            this.getElementsByClassName("note_toolbar")[0].style.display="block";
            this.style.zIndex="5";
            document.getElementById("background").style.zIndex=3;
        }},false)
    }
    getElementHeader(){
       return this.noteDOMElement.getElementsByClassName("header_note")[0];
    }
    getElementText(){
        return this.noteDOMElement.getElementsByClassName("text_note")[0];
    } 
    getHeight(){
       return this.noteDOMElement.clientHeight;
    }
    isDeleted(){
        if(this.noteDOMElement.closest("#"+this.spaceForNote.id))
            return false;
            else
                return true;
    }
}
Note._counter=0;
Note._arrayNotes=new Array();
Note._width=300;
Note._margin=10;

// колонки для распределения заметок
function Column(){
    this.array=new Array();
}
    Column.prototype.setNote=function(note){
     this.array.push(note);
 }
    Column.prototype.Height=function(){
        return this.array.reduce(function(height,item){
            return height+item.getHeight();
        },0);
       
}
    Column.columnWithMinHeight=function(arrayColumns){
    let arrayHeight=new Array(arrayColumns.length);
    for(let i=0;i<arrayHeight.length;i++){
        arrayHeight[i]=arrayColumns[i].Height();
    }
    
    let minHeight=Math.min.apply(Math,arrayHeight);
    let index=arrayHeight.indexOf(minHeight);
    return arrayColumns[index];
}

function request(url,note){
    let req=new XMLHttpRequest();
    let data="data="+JSON.stringify(note,["name","X","Y","data_num","colorBG","header","text"]);
    req.open('POST',url,true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.onreadystatechange=function(){
         if(req.readyState == 4 && req.status == 200) {          
           // alert(req.responseText);
    }
    }
    req.send(data);
}
function updateRequest(note){
    request('../request/updaterequest.php',note);
}
function insertRequest(note){
    request('../request/insertrequest.php',note);
}
function deleteRequest(note){
    request('../request/deleterequest.php',note);
}
function getNotesRequest(){
    let req=new XMLHttpRequest();
    req.open('POST','../request/getAllNotes.php',true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.onreadystatechange=function(){
         if(req.readyState == 4 && req.status == 200) {
            let notes=JSON.parse(req.responseText);
            
            let spfn=document.getElementById("space_for_notes");
            for(let index in notes){
                Note._arrayNotes.unshift(new Note(spfn));
                Note._arrayNotes[0].text=notes[index]['text'];
                Note._arrayNotes[0].header=notes[index]['header'];
                Note._arrayNotes[0].colorBG=notes[index]['color'];
                Note._arrayNotes[0].data_num=+notes[index]['num'];
                Note._arrayNotes[0].name=notes[index]['name'];
                Note._arrayNotes[0].CreateDOMElement();
                Note._arrayNotes[0].noteDOMElement.style.backgroundColor=notes[index]['color'];
                
            }
             Note._counter=Note._arrayNotes[0].data_num+1;
      Note.alignNote(); 
    }
    }
    req.send();
}

function exitClick(){
    document.querySelector(".exit").onclick=function(event){
        window.open('http://notes.ru/enter/enter.php','_self');
    }
}

//run script
    let Work=(function(){
    let click_indicator=false;
    document.onmousedown=function(event){
    let target=event.target;    
    if(target.closest("#create_note")==document.getElementById("create_note")){
        if(target.classList.contains("button_ok"))
            {
                 Note.makeNoteFromCreateElement();
                 click_indicator=false;
            }
        else{
          document.getElementById("create_note").getElementsByClassName('header_note')[0].style.display="block";
          document.getElementById("create_note").getElementsByClassName('toolbar')[0].style.display="block";  
          click_indicator=true;
        }
    }
    else{
        if(click_indicator){
            Note.makeNoteFromCreateElement();
           click_indicator=false;
          }
        }
    };
    window.onload=function(){
        getNotesRequest();    
    }
    exitClick();
})();
