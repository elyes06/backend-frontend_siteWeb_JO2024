let currentMenu = 0;

const targets = document.getElementsByClassName("inf");
let arrayTarget = [];
arrayTarget= [...targets];
const menu = document.getElementsByClassName("data__container")[0];

menu.addEventListener("click",(e)=>{
    console.log(e.target);
    currentMenu = e.target.dataset.id;
    if(currentMenu != null){
        arrayTarget.map( (element,index) =>{
            if(index == currentMenu){
                element.classList.remove("hidden");
            }else{
                element.classList.add("hidden");
            }
        })
    }
    
})

function showPopupResult(popupId) {
    var popup = document.getElementById(popupId);
    popup.style.display = "flex";
}

function closePopupResult(popupId) {
    var popup = document.getElementById(popupId);
    popup.style.display = "none";
}

