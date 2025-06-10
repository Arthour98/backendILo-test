// script.js
let checkboxes=document.querySelectorAll("input[type=checkbox].check");

function toggle(index)
{
let id=checkboxes[index].getAttribute("data-id");
let status=checkboxes[index].checked?
checkboxes[index].value="completed":checkboxes[index].value="ongoing";

let title=document.querySelectorAll("p.title");
let description=document.querySelectorAll("p.desc");
const statusObj={id:id,status:status};
const config={method:"POST",
headers:{"Content-Type":"application/json"},
body:JSON.stringify(statusObj)
}

console.log(statusObj);
fetch("toggle.php",config).then(response=>{return response.json()})
.then(data=>
{
if(data["status"]===true)
{
title[index].classList.add("done");
description[index].classList.add("done");
}
else
{
title[index].classList.remove("done");
description[index].classList.remove("done");
}
}
)
}

checkboxes.forEach((check,index)=>check.addEventListener("change",function()
{
toggle(index);
}))