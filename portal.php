<?php
session_start();

if(!isset($_SESSION["user"])){
    header("Location: login.php");
    exit();
}

if(isset($_GET["logout"])){
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
<title>AuraMarket Admin</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f4f6f9;
}

.wrapper{
display:flex;
height:100vh;
}

.sidebar{
width:240px;
background:#111827;
color:white;
padding:20px;
}

.sidebar h2{
margin-top:0;
}

.sidebar a{
display:block;
color:white;
text-decoration:none;
padding:10px;
margin-top:5px;
border-radius:6px;
}

.sidebar a:hover{
background:#374151;
}

.main{
flex:1;
padding:30px;
overflow:auto;
}

.header{
font-size:24px;
margin-bottom:20px;
}

.cards{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px;
margin-bottom:30px;
}

.card{
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 4px 12px rgba(0,0,0,0.1);
}

.grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:20px;
}

input{
width:100%;
padding:10px;
margin-top:6px;
margin-bottom:12px;
border-radius:6px;
border:1px solid #ccc;
}

button{
background:#2563eb;
color:white;
border:none;
padding:10px 18px;
border-radius:6px;
cursor:pointer;
}

button:hover{
background:#1e40af;
}

.actions{
display:flex;
gap:8px;
justify-content:center;
}

.btn-save{
background:#2563eb;
padding:6px 12px;
font-size:14px;
}

.btn-delete{
background:#ef4444;
padding:6px 12px;
font-size:14px;
}

.btn-save:hover{
background:#1e40af;
}

.btn-delete:hover{
background:#b91c1c;
}

th{
background:#f3f4f6;
text-align:center;
}

td{
text-align:center;
vertical-align:middle;
}

table{
width:100%;
border-collapse:collapse;
margin-top:10px;
}

th,td{
padding:8px;
border:1px solid #ddd;
}

.stat{
font-size:28px;
font-weight:bold;
}

</style>
</head>

<body>

<div class="wrapper">

<div class="sidebar">

<h2>AuraMarket</h2>

<a href="#">Dashboard</a>

<a href="https://auramarket-frontend.vercel.app/store" target="_blank">
Open Store
</a>

<a href="?logout=true">Logout</a>

</div>


<div class="main">

<div class="header">
Welcome <?php echo $_SESSION["user"]; ?>
</div>

<div class="cards">

<div class="card">
Products
<div class="stat" id="productCount">Loading...</div>
</div>

<div class="card">
Active Scans
<div class="stat">Live</div>
</div>

<div class="card">
System Status
<div class="stat">Online</div>
</div>

</div>


<div class="grid">

<div class="card" id="update">

<h2>Update Product</h2>

<select id="productSelect" onchange="loadProduct()">
<option value="">Select Product</option>
</select>

<br><br>

<input id="productId" placeholder="Product ID" readonly>

<input id="price" placeholder="Price">

<input id="ecoScore" placeholder="Eco Score">


<div id="supplySection" style="display:none">

<h3>Supply Chain Journey</h3>

<button id="addStageBtn" onclick="addEmptyStage()">
Add New Stage
</button>

<table id="supplyTable">

<thead>
<tr>
<th>Stage</th>
<th>Location</th>
<th>Timestamp</th>
<th>Actions</th>
</tr>
</thead>

<tbody></tbody>

</table>

</div>

<button onclick="updateProduct()">Update</button>

<p id="updateStatus"></p>

</div>


<div class="card" id="add">

<h3>Add New Product</h3>

Product ID
<input id="newProductId">

Name
<input id="newName">

Brand
<input id="newBrand">

Category
<input id="newCategory">

Manufacture Location
<input id="newLocation">

Carbon Footprint
<input id="newCarbon">

Eco Score
<input id="newEco">

Price
<input id="newPrice">

Manual Link
<input id="newManual">

<button onclick="addProduct()">Add Product</button>

<p id="addStatus"></p>

</div>

</div>

</div>

</div>


<script>

function updateProduct(){

let id=document.getElementById("productId").value

fetch(API+"/products/"+id,{
method:"PUT",
headers:{
"Content-Type":"application/json"
},
body:JSON.stringify({

price:document.getElementById("price").value,
ecoScore:document.getElementById("ecoScore").value

})
})
.then(res=>res.json())
.then(data=>{

document.getElementById("updateStatus").innerHTML="Product Updated!"

})

}


function addProduct(){

let product={

productId:document.getElementById("newProductId").value,
name:document.getElementById("newName").value,
brand:document.getElementById("newBrand").value,
category:document.getElementById("newCategory").value,
manufactureLocation:document.getElementById("newLocation").value,
carbonFootprint:document.getElementById("newCarbon").value,
ecoScore:document.getElementById("newEco").value,
price:document.getElementById("newPrice").value,
manualLink:document.getElementById("newManual").value

}

fetch(API+"/products",{
method:"POST",
headers:{
"Content-Type":"application/json"
},
body:JSON.stringify(product)
})
.then(res=>res.json())
.then(data=>{
document.getElementById("addStatus").innerText="Product Added"
})

}

function loadProductCount(){

fetch(API+"/products")
.then(res=>res.json())
.then(data=>{
document.getElementById("productCount").innerText=data.length
})

}


window.onload = function(){
loadProductCount()
}

</script>


<!-- To load products for updation-->

<script>

const API="https://auramarket-api.onrender.com/api"

function loadProducts(){

fetch(API+"/products")
.then(res=>res.json())
.then(data=>{

let select=document.getElementById("productSelect")

data.forEach(p=>{

let option=document.createElement("option")

option.value=p.productId
option.text=p.name+" ("+p.productId+")"

select.appendChild(option)

})

})

}

loadProducts()


function loadProduct(){

let id=document.getElementById("productSelect").value

if(!id){

document.getElementById("supplySection").style.display="none"
return

}

document.getElementById("supplySection").style.display="block"

fetch(API+"/products/"+id)
.then(res=>res.json())
.then(p=>{

document.getElementById("productId").value=p.productId
document.getElementById("price").value=p.price
document.getElementById("ecoScore").value=p.ecoScore

})

loadSupplyChain(id)

}

function loadSupplyChain(productId){

fetch(API+"/supplychain/"+productId)
.then(res=>res.json())
.then(data=>{

let table=document.querySelector("#supplyTable tbody")

table.innerHTML=""

data.forEach(stage=>{

let row=`
<tr>

<td>
<input value="${stage.stageName}" id="stage_${stage.id}">
</td>

<td>
<input value="${stage.location}" id="loc_${stage.id}">
</td>

<td>
<input value="${stage.timestamp}" id="time_${stage.id}">
</td>

<td>

<button onclick="updateStage('${stage.id}')">Save</button>

<button onclick="deleteStage('${stage.id}')"
style="background:red">
Delete
</button>

</td>

</tr>
`

table.innerHTML+=row

})

})
}

function updateStage(stageId){

let stage=document.getElementById("stage_"+stageId).value
let loc=document.getElementById("loc_"+stageId).value
let time=document.getElementById("time_"+stageId).value

fetch(API+"/supplychain/"+stageId,{

method:"PUT",

headers:{
"Content-Type":"application/json"
},

body:JSON.stringify({

stageName:stage,
location:loc,
timestamp:time

})

})
.then(res=>res.json())
.then(data=>{

alert("Stage Updated")

let productId=document.getElementById("productId").value

loadSupplyChain(productId)

})

}

function deleteStage(stageId){

fetch(API+"/supplychain/"+stageId,{

method:"DELETE"

})
.then(res=>res.json())
.then(data=>{

let productId=document.getElementById("productId").value

loadSupplyChain(productId)

})

}

function addEmptyStage(){

let productId=document.getElementById("productId").value

let stage=prompt("Stage Name")

let location=prompt("Location")

let timestamp=prompt("Timestamp (YYYY-MM-DD HH:MM)")

fetch(API+"/supplychain",{

method:"POST",

headers:{
"Content-Type":"application/json"
},

body:JSON.stringify({

productId:productId,
stageName:stage,
location:location,
timestamp:timestamp

})

})
.then(res=>res.json())
.then(data=>{

loadSupplyChain(productId)

})

}

</script>

</body>
</html>