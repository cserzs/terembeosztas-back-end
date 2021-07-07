<div ng-app="RoomManager" ng-controller="ctrl">

<div class="container-fluid"  ng-cloak>

    <div class="row topGap">
        <div class="col-sm-2">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle daydropdown" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">[[ napok[selectedDay] ]]</button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <button ng-repeat="nap in napok" class="dropdown-item" ng-click="changeDay($index)">[[ nap ]]</button>
                </div>
            </div>
        </div>
        <div class="col-sm-1"><button class="btn btn-primary" ng-click="showRoomCheckDialog()">Ellenőrzés</button></div>
        <div class="col-sm-1"><button class="btn btn-primary" ng-click="confirmDailyDelete()">Napi beosztás törlése</button></div>
    </div>

    
    <div class="table-responsive fixed_first_col_div topGap">
        <table class="table fixed_col_table table-hover">
            <tr>
                <th class="headcol">
                
                </th>
                <th ng-repeat="osztaly in osztalyok" class="text-center">[[ osztaly.nev ]]</th>
            </tr>
            <tr ng-repeat="idopont in idopontok">
                <th class="headcol">[[ idopont ]]. óra</th>
                <td ng-repeat="osztaly in osztalyok" class="classcolumn text-center">
                    <span ng-repeat="roomId in roomBindings[osztaly.id][idopont] track by $index">
                        <a href="" ng-click="handleRoomClick(osztaly.id, idopont, $index, roomId)" class="badge badge-light">[[ getRoom(roomId).rovid_nev ]]</a>/
                    </span>
                    <span ng-click="bindRoom(osztaly.id, idopont)" class=" cursor badge badge-info">+</span>
                </td>
            </tr>
        </table>
    </div>

</div>    

    
<div class="loadingScreen" ng-show="!isLoaded">
    <div><md-progress-circular md-mode="indeterminate"></md-progress-circular><br/>Adatok feldolgozása...</div>
</div>
    
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>

<script type="text/javascript" src="/js/angular.v1.6.7.min.js"></script>
<script type="text/javascript" src="/js/angular-sanitize.v1.6.7.min.js"></script>

<script type="text/javascript" src="/js/angular-animate.v1.6.6.min.js"></script>
<script type="text/javascript" src="/js/angular-aria.v1.6.6.min.js"></script>
<script type="text/javascript" src="/js/angular-messages.v1.6.6.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angular_material/1.1.0/angular-material.min.js"></script>

<script type="text/javascript">
var app = angular.module('RoomManager', ['ngMaterial', 'ngSanitize']);

app.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

app.controller('ctrl', function($scope, $mdDialog, $http) {
    var server = "";
    $scope.napok = ["Hétfő", "Kedd", "Szerda", "Csütörtök", "Péntek"];
    $scope.selectedDay = 0;
    $scope.isLoaded = false;
    $scope.termek = [];
    $scope.nincsora = {
        id: 0,
        nev: '-',
        rovid_nev: '-',
        megjegyzes: ''
    };
    $scope.osztalyok = [];
    $scope.idopontok = [0, 1, 2, 3, 4, 5, 6, 7, 8];
    //  roomBindings[osztalyid][idopont][pozicio] => teremid
    $scope.roomBindings = [];

    //  ora: [foglalt termek]
//    $scope.timeToRoomsBindings = {
//        '1': [3],
//        '2': [4, 5]
//    };
    $scope.timeToRoomsBindings = [];
    
    $scope.getRoom = function(roomId)
    {
        if (roomId == 0) return $scope.nincsora;
        for(var i = 0; i < $scope.termek.length; i++)
            if ($scope.termek[i].id == roomId) return $scope.termek[i];
        return $scope.nincsora;
    };
    $scope.getSchoolclass = function(classId)
    {
        for(var i = 0; i < $scope.osztalyok.length; i++)
            if ($scope.osztalyok[i].id == classId) return $scope.osztalyok[i];
        return { nev: '-' };
    };
    $scope.handleRoomClick = function(osztalyid, idopont, pozicio, teremid)
    {
        //  if utolso pozicio: delete
        //  else: csere
        console.log('handle room click: ' + osztalyid + ", " + idopont + ", " + pozicio + ", " + teremid);
        
        if ($scope.roomBindings[osztalyid][idopont].length - 1 == pozicio) removeRoom(osztalyid, idopont, pozicio, teremid);
        else {
            var title = "Teremcsere: " + $scope.getSchoolclass(osztalyid).nev + ", " + idopont + ". óra (" + $scope.getRoom(teremid).nev + ")";
            showRoomSelectionDialog(osztalyid, idopont, title, function(teremid) {
                changeRoom(osztalyid, idopont, pozicio, teremid);
            });
        }
    };
    $scope.bindRoom = function(osztalyid, idopont)
    {
        console.log('bind room: ' + osztalyid + ", " + idopont);
        var title = "Új terem: " + $scope.getSchoolclass(osztalyid).nev + ", " + idopont + ". óra";
        showRoomSelectionDialog(osztalyid, idopont, title, function(teremid) {
            addRoom(osztalyid, idopont, teremid);
        });
    };
    $scope.changeDay = function(dayid)
    {
        $scope.isLoaded = false;
        $scope.selectedDay = dayid;
        loadRoomBindings();
    };
    
    
    
    
    
    
    function addRoom(osztalyid, idopont, teremid)
    {
//        console.log("add room: " + osztalyid + ", " + idopont + ", " + teremid);
        
        if ($scope.roomBindings[osztalyid] == undefined) $scope.roomBindings[osztalyid] = [];
        if ($scope.roomBindings[osztalyid][idopont] == undefined) $scope.roomBindings[osztalyid][idopont] = [];

        if (teremid != 0 && $scope.roomBindings[osztalyid][idopont].indexOf(teremid) > -1)
        {
            console.log("nem lehet megegyszer hozzaadni!");
            return;
        }
        var urespozicio = getNextEmptyPosition(osztalyid, idopont);        
        $scope.roomBindings[osztalyid][idopont][urespozicio] = teremid;

        if ($scope.timeToRoomsBindings[idopont] == undefined) $scope.timeToRoomsBindings[idopont] = [];
        $scope.timeToRoomsBindings[idopont].push(teremid);

        console.log("send: " + osztalyid + " / " + $scope.selectedDay + " / " + idopont + " / " + urespozicio + " / " + teremid);
        $http.post(server + '/api/roombindings', {
            'osztalyid': osztalyid,
            'nap': $scope.selectedDay,
            'idopont': idopont,
            'pozicio': urespozicio,
            'teremid': teremid}).
        then(function(successResponse) {
            //  success
        },
        function(errorResponse) {
            //  error
        });

    };
    function changeRoom(osztalyid, idopont, pozicio, teremid)
    {
        console.log("change room: " + osztalyid + ", " + idopont + ", " + pozicio + ", " + teremid);
        $http.put(server + '/api/roombindings/' + osztalyid +'/' + $scope.selectedDay + '/' + idopont + '/' + pozicio + '/' + teremid).
            then(function(successResponse) {
                var regiteremid = $scope.roomBindings[osztalyid][idopont][pozicio];
                var index = $scope.timeToRoomsBindings[idopont].indexOf(regiteremid);
                $scope.timeToRoomsBindings[idopont].splice(index, 1);
                $scope.timeToRoomsBindings[idopont].push(teremid);
                $scope.roomBindings[osztalyid][idopont][pozicio] = teremid;
            }, function(errorResponse) {
                console.log("chnage room hiba");
            });
    };
    function removeRoom(osztalyid, idopont, pozicio, teremid)
    {
        $http.delete(server + '/api/roombindings/' + osztalyid +'/' + $scope.selectedDay + '/' + idopont + '/' + pozicio + '/' + teremid).
            then(function(successResponse) {
                var index = $scope.timeToRoomsBindings[idopont].indexOf(teremid);
                $scope.timeToRoomsBindings[idopont].splice(index, 1);
                $scope.roomBindings[osztalyid][idopont].splice(pozicio, 1);
            }, function(errorResponse) {
                console.log("torles hiba");
            });
    };
    
    function showRoomSelectionDialog(osztalyid, idopont, title, successCallback)
    {
        console.log("classId = " + osztalyid + ", idopont = " + idopont);
        var roomList = [];
        roomList.push({
            id: 0,
            name: 'nincs óra',
            used: 0
        });
        for(var i = 0 ; i < $scope.termek.length; i++)
        {
            var temp = {
                id: $scope.termek[i].id,
                name: $scope.termek[i].nev,
                desc: $scope.termek[i].megjegyzes,
                used: (Array.isArray($scope.timeToRoomsBindings[idopont]) && $scope.timeToRoomsBindings[idopont].indexOf($scope.termek[i].id) > -1) ? 1 :0
            };
            roomList.push(temp);
        }
        $mdDialog.show({
            controller: RoomSelectionController,
            templateUrl: '/partials/_roomselect_dialog.html?v37',
            parent: angular.element(document.body),
            clickOutsideToClose:true,
            fullscreen: true,
            locals: { roomList: roomList, title: title}
        })
        .then(function(roomId) {
            console.log("selected room: " + roomId);
            successCallback(roomId);
        }, function() {
            console.log("nincs room kivalasztva");
        });
    };
    function RoomSelectionController($scope, $mdDialog, roomList, title)
    {
        $scope.roomList = roomList;
        $scope.title = title;
        $scope.roomDescription = '&nbsp;';
        $scope.hide = function() {
            $mdDialog.hide();
        };
        $scope.cancel = function() {
            $mdDialog.cancel();
        };
        $scope.answer = function(roomId) {
            $mdDialog.hide(roomId);
        };
        $scope.showDesc = function(roomId) {
            if (roomId < 0) $scope.roomDescription = '&nbsp;';
            else {
                var room = getRoom(roomId);
                if (typeof room.desc == "string" && room.desc.length > 0)
                {
                    $scope.roomDescription = room.name + ": " + room.desc;
                }
            }
        }
        function getRoom(roomId) {
            for(var i = 0; i < $scope.roomList.length; i++)
                if ($scope.roomList[i].id == roomId) return $scope.roomList[i];
        }
    }
    
    $scope.showRoomCheckDialog = function() {
        $scope.isLoaded = false;        
        $http.get('/api/roombindings/checkduplicate/' + $scope.selectedDay)
            .then(function(response) {
                var roomList = [];
                for(var i = 0; i < $scope.idopontok.length; i++) roomList[i] = [];
                for(var i = 0; i < response.data.length; i++) {
                    var idopont = response.data[i]['idopont'];
                    var terem_id = response.data[i]['terem_id'];
                    var num = response.data[i]['num'];
                    roomList[idopont].push({
                        nev: $scope.getRoom(terem_id).nev,
                        num: num
                    });
                }
                $scope.isLoaded = true;
                $mdDialog.show({
                    controller: RoomCheckController,
                    templateUrl: '/partials/_roomcheck_dialog.html?v8',
                    parent: angular.element(document.body),
                    clickOutsideToClose:true,
                    fullscreen: true,
                    locals: { roomList: roomList}
                })
                .then(function(roomId) {
                }, function() {
                });
            });
    };
    function RoomCheckController($scope, $mdDialog, roomList) {
        $scope.roomList = roomList;
        $scope.hide = function() {
            $mdDialog.hide();
        };
        $scope.cancel = function() {
            $mdDialog.cancel();
        };
    };

    $scope.confirmDailyDelete = function() {
        if (confirm("Biztos törlöd az egész napi terembeosztást?\nNem lehet visszavonni!!")) {
            $http.delete(server + '/api/dailyroombindings/' + $scope.selectedDay).
                then(function(successResponse) {
                    $scope.changeDay($scope.selectedDay);
                }, function(errorResponse) {
                    console.log("torles hiba");
                });
        } 
    };
    
    
    function getNextEmptyPosition(osztalyid, idopont)
    {
        if ($scope.roomBindings[osztalyid] == undefined) return 0;
        if ($scope.roomBindings[osztalyid][idopont] == undefined) return 0;
        return $scope.roomBindings[osztalyid][idopont].length;
    };
    
    function load()
    {
        $http.get(server + '/api/classes').
            then(function(response) {
                $scope.osztalyok = response.data;
                return $http.get(server + '/api/rooms');
            }).
            then(function(response) {
                $scope.termek = response.data;
                loadRoomBindings();
            });
    };
    function loadRoomBindings()
    {
        $http.get(server + '/api/assignment/day/'+$scope.selectedDay).
            then(function(response) {
                $scope.roomBindings = [];
                $scope.timeToRoomsBindings = [];
                for(var i = 0; i < response.data.length; i++)
                {
                    var osztalyid = parseInt(response.data[i]['osztaly_id']);
                    var idopont = parseInt(response.data[i]['idopont']);
                    var pozicio = parseInt(response.data[i]['pozicio']);
                    var teremid = parseInt(response.data[i]['terem_id']);

                    if ($scope.roomBindings[osztalyid] == undefined) $scope.roomBindings[osztalyid] = [];
                    if ($scope.roomBindings[osztalyid][idopont] == undefined) $scope.roomBindings[osztalyid][idopont] = [];
                    $scope.roomBindings[osztalyid][idopont][pozicio] = teremid;
                    
                    
                    if ($scope.timeToRoomsBindings[idopont] == undefined) $scope.timeToRoomsBindings[idopont] = [];
                    $scope.timeToRoomsBindings[idopont].push(teremid);
                }
                $scope.isLoaded = true;
            });  
            
    };

    load();

});
</script>