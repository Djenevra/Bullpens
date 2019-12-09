<!DOCTYPE html>
<html lang="en">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .container {
        border-color: #008a77; /* Цвет границы */
        border-style: solid; /* Стиль границы */
        padding: 5px; /* Поля вокруг текста */
        width: 800px;
        margin: 100px;

        }
    .bullpen1s, .bullpen2s, .bullpen3s, .bullpen4s {
        border-color: #008a77; /* Цвет границы */
        border-style: solid; /* Стиль границы */
        padding: 5px; /* Поля вокруг текста */
        font-weight: 100;
        margin: 10px;
    }
    h1{
        text-align: center;
        margin-top: 100px;

    }
    .days {
        border-color: gray; /* Цвет границы */
        border-style: solid; /* Стиль границы */
        padding: 5px; /* Поля вокруг текста */
        font-weight: 100;
        margin: 10px;
        width: 20px; 
    }
</style>
<script>
var days = 0
var counter = 10;
var test;
var bullpens = document.getElementsByClassName('bullpen'); //collects all four bullpens in the DOM

function dayCounter() { //count days passed, and add one more new sheep
    days += 1
    counter += 1;
    var daysElement = document.getElementById('days'); //gets days div 
    daysElement.innerHTML = days; //inserts days var into days div
    addSheep(); //calls addSheep() function
}

function addSheep() { 
   var bullpensNameArray = []; //bullpen's name array with more than one sheep 
   let bullpenWithOneSheep = []; // pullpen's name with one sheep

    for (var i = 0, len = bullpens.length; i < len; i++) { //loop through all bullpen
        var bullpen = bullpens[i];
        var sheeps = bullpen.getElementsByClassName('sheep'); //collect all sheep inside the bullpen
        if (sheeps.length > 1) { //checks sheeps quantity in the bullpen, if it is more than one it gets name of bullpen
            bullpensNameArray.push(bullpen.className.split(" ")[0]); //collects array of pullpens name
        }
    }
    $.ajax({ //send bullpen's name array to backend 
        type: "POST",
        url: '/addsheep',
        data: {"_token": $('meta[name="csrf-token"]').attr('content'), 'bullpensNameArray': bullpensNameArray, 'counter': counter }, //token is needed to make ajax request to work properly
        success: function (response) { //gets back array of new sheeps list from backend
            let div = document.createElement('div'); //creates div
            div.className = "sheep"; //gives class to created div
            div.innerHTML = response.sheepList.slice(-1)[0].sheep_name;  //insert last sheep into created div
            //$(`.${response.bullpenName}`).append(div); //append div into bullpen
               checkMaxAndMinBullpens();
            }
        });
};

function getBullpen() { //gets bullpen from which the sheep will be killed
    let bullpens = ['bullpen1s', 'bullpen2s', 'bullpen3s', 'bullpen4s'];
    let bullpensList = [];
    for (var i = 0, len = bullpens.length; i < len; i++) {  //loop through all bullpens
        let bullpen = bullpens[i];
        let sheeps = $(`.${bullpen}`).children(); //get all sheeps of each bullpen
        if (sheeps.length > 1) { //checks if bullpen has more than one sheep
        bullpensList.push(bullpen); //collect all pullpens where quantity of sheep is more than one
        }
    }
    let bullpenName = bullpensList[Math.floor(Math.random()*bullpensList.length)]; //select one random bullpen from bullpens array 
        killSheep(bullpenName);  
}

function checkMaxAndMinBullpens () { //this function for relocating sheeps
    let bullpensArrayListWithSheeps = []; //array that contains all bullpens and number of sheeps in each of them
    let bullpenArrayListWithMinSheeps = []; //array that contains only bullpens with minimum sheeps
    let bullpenArrayListWithMaxSheeps = []; //array that contains only bullpens with maximum sheeps
    let bullpens = ['bullpen1s', 'bullpen2s', 'bullpen3s', 'bullpen4s']; //bullpens name array
    for (var i = 0, len = bullpens.length; i < len; i++) {  //loop through all bullpens
        let sheeps = $(`.${bullpens[i]}`).children(); //get all sheeps of each bullpen
        bullpensArrayListWithSheeps.push({'bullpen': bullpens[i], 'sheeps_num': sheeps.length}); //collect all pullpens where quantity of sheep is more than one
    }
    let max = Math.max.apply(Math, bullpensArrayListWithSheeps.map(function(o) { //get max num of sheeps
        return o.sheeps_num; 
    }));
    let min = Math.min.apply(Math, bullpensArrayListWithSheeps.map(function(o) { //get min num of sheeps
        return o.sheeps_num; 
    }));
    for (var i = 0, len = bullpensArrayListWithSheeps.length; i < len; i++) { //collect array of bullpens with max and min num of sheeps
        if (bullpensArrayListWithSheeps[i].sheeps_num == 1) {
            bullpenArrayListWithMinSheeps.push({'bullpen':bullpensArrayListWithSheeps[i].bullpen });
        } else if (bullpensArrayListWithSheeps[i].sheeps_num == max) {
            bullpenArrayListWithMaxSheeps.push({'bullpen':bullpensArrayListWithSheeps[i].bullpen});
        } 
    }
    $.ajax({
        type: "POST",
        url: '/relocatesheep',
        data: {"_token": $('meta[name="csrf-token"]').attr('content'), 'bullpenArrayListWithMinSheeps': bullpenArrayListWithMinSheeps, 'bullpenArrayListWithMaxSheeps': bullpenArrayListWithMaxSheeps, 'counter': counter },
        success: function (response) {
            let minSheepHtml = [];
            let maxSheepHtml = [];
            if (response.relocate) {
                response.currentBullpenWithMaxSheeps.forEach(function (arrayItem){
                response.currentBullpenWithMaxSheeps[arrayItem];
                maxSheepHtml +=  `<div class=sheep>${arrayItem.sheep_name}</div>`
            });
            response.currentBullpenWithMinSheeps.forEach(function (arrayItem){
                response.currentBullpenWithMinSheeps[arrayItem];
                minSheepHtml +=  `<div class=sheep>${arrayItem.sheep_name}</div>`
            });
            console.log('here', minSheepHtml, maxSheepHtml, response.currentBullpenWithMinSheepsName, response.currentBullpenWithMaxSheepsName);
            $(`.${response.currentBullpenWithMinSheepsName}`).html(minSheepHtml);
            $(`.${response.currentBullpenWithMaxSheepsName}`).html(maxSheepHtml);
            } else {
                return false
            }
            
        }
    });
};    

function killSheep(bullpenName) {
    $.ajax({
        type: "POST",
        url: '/killsheep',
        data: {"_token": $('meta[name="csrf-token"]').attr('content'), 'bullpen': bullpenName, 'counter': counter },
        success: function (response) {
            let sheepsHtml = [];
            console.log('here', response.bullpen.length);
            response.bullpen.forEach(function (arrayItem){
                response.bullpen[arrayItem];
                sheepsHtml +=  `<div class=sheep>${arrayItem.sheep_name}</div>`
            });
            $(`.${bullpenName}`).html(sheepsHtml);
    }
    });
}

    var counter= 0;
    if( typeof localStorage.counter !== 'undefined' ) 
    counter = parseInt(localStorage.counter);

    setInterval(function () {
        var daysElement = document.getElementById('days');
        daysElement.innerHTML = counter;
        counter +=1
        localStorage.setItem('counter',counter);
        }, 10000);

setInterval(dayCounter, 10000);
setInterval(getBullpen, 100000);
</script>
<h1 class=head >Bullpens</h1>
<div class=container id=container>
<tbody>
    <h3>Загон 1</h3>
    <div class="bullpen1s bullpen ">@foreach ($bullpen1 as $bullpen1)
        <tr>
            <!-- Task Name -->
            <td class="table-text">
                <div  class=sheep>{{ $bullpen1->sheep_name }}</div>
            </td>

            <td>
                <!-- TODO: Delete Button -->
            </td>
        </tr>
    @endforeach
    </div>
    <h3>Загон 2</h3>
    <div class="bullpen2s bullpen"> @foreach ($bullpen2 as $bullpen2)
        <tr>
            <!-- Task Name -->
            <td class="table-text">
                <div class=sheep>{{ $bullpen2->sheep_name }}</div>
            </td>

            <td>
                <!-- TODO: Delete Button -->
            </td>
        </tr>
    @endforeach
    </div>
    <h3>Загон 3</h3>
    <div class="bullpen3s bullpen"> 
    @foreach ($bullpen3 as $bullpen3)
        <tr>
            <!-- Task Name -->
            <td class="table-text">
                <div class=sheep>{{ $bullpen3->sheep_name }}</div>
            </td>

            <td>
                <!-- TODO: Delete Button -->
            </td>
        </tr>
    @endforeach
    </div> 
    <h3>Загон 4</h3>
    <div class="bullpen4s bullpen"> @foreach ($bullpen4 as $bullpen4)
        <tr>
            <!-- Task Name -->
            <td class="table-text">
               <div class=sheep>{{ $bullpen4->sheep_name }}</div>
            </td>

            <td>
                <!-- TODO: Delete Button -->
            </td>
        </tr>
    @endforeach
    </div> 
    Количество пройденных дней <p class=days id=days></p>
</tbody>
</div>
</html>