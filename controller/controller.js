app.controller('changeLangCtrl', function ($scope, $http) {
	$scope.englang = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/chalanajax.php',
			data: { lang: '1' },
		}).then(function successCallback(response) {
			window.location.reload();
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.hauslang = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/chalanajax.php',
			data: { lang: '2' },
		}).then(function successCallback(response) {
			window.location.reload();
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});
app.controller('sUserCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/suserajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.userList = response.data;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'profile',profilefor:'aduser' }
	}).then(function successCallback(response) {
		$scope.profiles = response.data;
		//window.location.reload();
	});
	$scope.edituser = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.fname = response.data[0].fname;
			$scope.lname = response.data[0].lname;
			$scope.active = response.data[0].active;
			$scope.email = response.data[0].email;
			$scope.id = response.data[0].id;
					}, function errorCallback(response) {
						// console.log(response);
					});
				}
				$scope.idd = function (id) {
					$scope.id = id;
				}
				$scope.updateaccess = function (id) {
					$http({
						method: 'post',
						url: '../ajax/suserajax.php',
						data: {
							action: 'updateaccess',
							weekendaccess: $scope.weekendaccess,
							weekendcontrol: $scope.weekendcontrol,
							stime:$scope.stime,
							etime:$scope.etime,
							id:id
						},
					}).then(function successCallback(response) {
						$scope.isHide = true;
						$scope.isHideCancel = true;
						$scope.isHideOk = false;
						$scope.isLoader = false;
						$scope.isMainLoader = false;
						$("#accessBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
						//window.location.reload();
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.usercheck = function ($event, userName) {
		$scope.loadgif = true;
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { userName: userName, action: 'usercheck' },
		}).then(function successCallback(response) {
			$scope.loadgif = true;
			if (response.data > 0) {
				$scope.colstyle = {
					"background-color": "red",
					"color": "white"
				}
				$('.check').html('<span style="position: absolute;"> <img src="../common/images/error.png" style="display:inline-block;margin-left:0px;"/>User Name is already taken.</span>');
				$scope.isHide = true;
				$scope.usrform = true;
			}
			else {
				$('.check').html('<span style="position: absolute;"> <img src="../common/images/accept.png" style="display:inline-block;margin-left:0px;"/>User Name is avaliable</span>');
				$scope.colstyle = {
					"background-color": "white"
				}
				$scope.isHide = false;
				$scope.usrform = false;
			}
		}, function errorCallback(response) {
			//console.log(response);
		});
	}
	$scope.createuser = function () {
		$scope.isHide = true;
		$scope.isHideReset = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;

		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: {
				action: 'insertsysuser',
				userName: $scope.userName,
				firstName: $scope.firstname,
				lastName: $scope.lastname,
				active: $scope.active,
				password: $scope.sysuserpassword,
				repassword: $scope.sysuserrepassword,
				startDate: $scope.startDate,
				endDate: $scope.endDate,
				email: $scope.email,
				profile: $scope.profile
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#UserCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
			//window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.editotpdetails = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'otpchange' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.otptype = response.data[0].odynamic;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.otytypeupdate = function (id) {
		$scope.isHide = true;
		$scope.isHideCancel = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: {
				id: id,
				action: 'otytypeupdate',
				otptype: $scope.otptype,
				sendmail: $scope.mail
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#OtpTypeBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.uppassreset = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: {
				id: id,
				action: 'passreset',
				password: $scope.password,
				repassword: $scope.repassword
			},
		}).then(function successCallback(response) {
			alert(response.data)
			window.location.reload();
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.editsotpdetails = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'scontrol' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.spin = response.data[0].pin;
			$scope.stotp = response.data[0].ovalue;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.regenerate = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'regenerate' },
		}).then(function successCallback(response) {
			if (response.data[0].error == 0) {
				$scope.key = response.data[0].msg;
			}
			else {
				alert(response.data[0].msg);
				window.location.reload();
			}
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.editcontrol = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'control' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.fstlogin = response.data[0].fstlogin;
			$scope.locked = response.data[0].locked;
			$scope.ltime = response.data[0].ltime;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.lock = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'lock' },
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.changefstlogn = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'changefstlogn' },
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.changefstlogy= function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'changefstlogy' },
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.unlock = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'unlock' },
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.getcurrentotp = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'getcurrentotp' },
		}).then(function successCallback(response) {
			$("#currmsg").html(response.data);

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.updatesotpvalue = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: {
				id: id,
				otpvalue: $scope.stotp,
				action: 'ssotpupdate'
			},
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.updatesotppin = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: {
				id: id,
				pin: $scope.spin,
				action: 'pinupdate'
			},
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.editdynamicotpdetails = function (index, id) {
		$http({
			method: 'post',
			url: '../common/qr.php',
			data: { id: id, action: 'editotpdetails' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.img = response.data.img;
			$scope.issuer = response.data[0].issuer;
			$scope.key = response.data[0].key;
			$scope.otptype = response.data[0].otptype;
			$scope.spin = response.data[0].pin;
			$scope.interval = response.data[0].interval;
			$scope.algorithm = response.data[0].algorithm;
			$scope.otplength = response.data[0].digits;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.sendmail = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'sendmail' },
		}).then(function successCallback(response) {
			alert(response.data);
		}, function errorCallback(response) {
			// console.log(response);
		});
	}

	$scope.updateuser = function (id) {
		$scope.isHideOk = true;
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: {
				id: $scope.id,
				fname: $scope.fname,
				lname: $scope.lname,
				active: $scope.active,
				email: $scope.email,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#UserBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('authCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.splCharRes = "/^(?=.*[A-Za-z])(?=.*\d)(?=.~!@#$*_-/\\|-])[A-Za-z\d$@('@')$!%*#?&{}[\]<>()^+=;:'&quot;/\\|-]{8,20}$/";
	$http({
		method: 'post',
		url: '../ajax/authajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.authlist = response.data;
	});
	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/authajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.code = response.data[0].code;
			$scope.active = response.data[0].active;
			$scope.assignable = response.data[0].assignable;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$http({
			method: 'post',
			url: '../ajax/authajax.php',
			data: {
				id: $scope.id,
				code: $scope.code,
				active: $scope.active,
				assignable: $scope.assignable,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#AuthCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isHideOk = true;
		$http({
			method: 'post',
			url: '../ajax/authajax.php',
			data: {
				id: $scope.id,
				code: $scope.code,
				active: $scope.active,
				assignable: $scope.assignable,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#AuthBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('appApproveCtrl', function ($scope, $http) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.checkdate = function (startDate,endDate){
		var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
		var currdate = new Date();
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
			$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
			alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
		}
		else {
			$scope.isQueryDi = false;
		}
	}
	$scope.query = function () {
		var startDate =  $scope.startDate;
		var endDate =  $scope.endDate;
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		var currdate = new Date();
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
			$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
			alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
		}
		else {
		$scope.isLoaderMain = true;
		$http({
			method: 'post',
			url: '../ajax/appapproveajax.php',
			data: { startDate: $scope.startDate, endDate: $scope.endDate, action: 'query' },
		}).then(function successCallback(response) {
			$scope.approveList = response.data;
			$scope.isLoaderMain = false;
			//	$scope.isHide = true;

		}, function errorCallback(response) {
			// console.log(response);
		});
		}
	}
	$scope.resetappr = function () {
		$scope.isHide = false;
	}
	$scope.edit = function (index, id) {
		 $http({
			url: '../ajax/load.php',
			method: "POST",
			//Content-Type: 'application/json',
			params: { action: 'active', for: 'partycatype' }
			}).then(function successCallback(response) {
			$scope.partycatypes = response.data;
			//window.location.reload();
			});
		$http({
			method: 'post',
			url: '../ajax/appapproveajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.id = response.data[0].id;
			$scope.outletname = response.data[0].name;
			$scope.palogin = response.data[0].palogin;
			$scope.type = response.data[0].type;
			$scope.code = response.data[0].code;
		}, function errorCallback(response) {
			// console.log(response);
		});
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'services' },
		}).then(function successCallback(response) {
			$scope.services = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.isHide = false;
	$scope.isHideOk = true;
	$scope.detail = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/appapproveajax.php',
			data: { id: id, action: 'detail' },
		}).then(function successCallback(response) {
			$scope.id = response.data[0].id;
			$scope.outletname = response.data[0].name;
			$scope.country = response.data[0].country;
			$scope.name = response.data[0].name;
			$scope.type = response.data[0].type;
			$scope.category = response.data[0].category;
			$scope.time = response.data[0].time;
			$scope.status = response.data[0].status;
			$scope.address1 = response.data[0].address1;
			$scope.address2 = response.data[0].address2;
			$scope.localgovt = response.data[0].localgovt;
			$scope.state = response.data[0].state;
			$scope.zip = response.data[0].zip;
			$scope.tax = response.data[0].tax;
			$scope.email = response.data[0].email;
			$scope.mobile = response.data[0].mobile;
			$scope.fax = response.data[0].fax;
			$scope.work = response.data[0].work;
			$scope.cpm = response.data[0].cpm;
			$scope.cpn = response.data[0].cpn;
			$scope.palogin = response.data[0].palogin;
			$scope.code = response.data[0].code;
			$scope.entrycomments = response.data[0].entrycomments;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.refresh = function (id, type) {
		window.location.reload();
	}
	$scope.approve = function (id, type) {
		$scope.isLoader = true;
			$scope.isMainLoader = true;
		var i = 0;
		var arr = [];
		$('.selectedServices:checked').each(function () {
			arr[i++] = $(this).val();
		});
		$http({
			method: 'post',
			url: '../ajax/appapproveajax.php',
			data: {
				id: id,
				parentType: $scope.parentType,
				creditLimit: $scope.creditLimit,
				dailyLimit: $scope.dailyLimit,
				advanceAmount: $scope.advanceAmount,
				minimumBalance: $scope.minimumBalance,
				selectedServices: arr,
				comments: $scope.comments,
				action: 'approve',
				type: type,
				partycatype:$scope.partycatype
			},
		}).then(function successCallback(response) {
			$("#ApproveBody").html("<h3 style='text-align:center'>" + response.data + "</h3>");
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});
app.controller('appAuthorizeCtrl', function ($scope, $http, $filter) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.checkdate = function (startDate,endDate){
		var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
		var currdate = new Date();
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
			$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
			alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
		}
		else {
			$scope.isQueryDi = false;
		}
	}
	$scope.query = function () {
		$scope.tablerow = true;
		var startDate =  $scope.startDate;
		var endDate =  $scope.endDate;
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		var currdate = new Date();
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
			$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
			alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
		}
		else {
			$scope.isLoaderMain = true;
			$http({
				method: 'post',
				url: '../ajax/appauthorizeajax.php',
				data: { startDate: $scope.startDate, endDate: $scope.endDate, action: 'query' },
			}).then(function successCallback(response) {
				$scope.approveList = response.data;
				$scope.isLoaderMain = false;
				// $scope.isHide = true;
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
	}
	$scope.resetappr = function () {
			$scope.tablerow = false;
		$scope.isHide = false;
		$scope.startDate = new Date();
		$scope.endDate = new Date();


	}
	$scope.edit = function (index, id, code, rtype) {
		$http({
			url: '../ajax/load.php',
			method: "POST",
			//Content-Type: 'application/json',
			params: { action: 'active', for: 'partycatype' }
			}).then(function successCallback(response) {
			$scope.partycatypes = response.data;
			//window.location.reload();
			});
		$http({
			method: 'post',
			url: '../ajax/appauthorizeajax.php',
			data: { id: id, action: 'edit', rtype: rtype },
		}).then(function successCallback(response) {
			$scope.id = response.data[0].id;
			$scope.outletname = response.data[0].name;
			$scope.approverComment = response.data[0].approverComment;
			$scope.type = response.data[0].type;
			$scope.creditLimit = response.data[0].climit;
			$scope.dailyLimit = response.data[0].dlimit;
			$scope.advanceAmount = response.data[0].alimit;
			$scope.minimumBalance = response.data[0].mlimit;
			$scope.partycatype = response.data[0].sstype;
			$scope.palogin = response.data[0].palogin;
			$scope.code = response.data[0].code;
		}, function errorCallback(response) {
			// console.log(response);
		});

		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'services' },
		}).then(function successCallback(response) {
			$scope.services = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
		$http({
			method: 'post',
			url: '../ajax/appauthorizeajax.php',
			data: { code: code, action: 'userservice' },
		})
			.then(function successCallback(response) {
				angular.forEach(response.data, function (value, key) {
					$('input[name="selectedServices"][value="' + value.ids.toString() + '"]').prop("checked", true);
				});
			}, function errorCallback(response) { // console.log(response);
			});
	}
	$scope.isHide = false;
	$scope.isHideOk = true;
	$scope.detail = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/appauthorizeajax.php',
			data: { id: id, action: 'detail' },
		}).then(function successCallback(response) {
			$scope.id = response.data[0].id;
			$scope.outletname = response.data[0].name;
			$scope.country = response.data[0].country;
			$scope.name = response.data[0].name;
			$scope.type = response.data[0].type;
			$scope.category = response.data[0].category;
			$scope.time = response.data[0].time;
			$scope.status = response.data[0].status;
			$scope.address1 = response.data[0].address1;
			$scope.address2 = response.data[0].address2;
			$scope.localgovt = response.data[0].localgovt;
			$scope.state = response.data[0].state;
			$scope.zip = response.data[0].zip;
			$scope.tax = response.data[0].tax;
			$scope.email = response.data[0].email;
			$scope.mobile = response.data[0].mobile;
			$scope.fax = response.data[0].fax;
			$scope.work = response.data[0].work;
			$scope.cpm = response.data[0].cpm;
			$scope.cpn = response.data[0].cpn;
			$scope.comment = response.data[0].comment;
			$scope.appcomment = response.data[0].appcomment;
			$scope.appdate = response.data[0].appdate;
			$scope.palogin = response.data[0].palogin;
			$scope.code = response.data[0].code;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.refresh = function (id, type) {
		window.location.reload();
	}
	$scope.authorize = function (id, type) {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		var i = 0;
		var arr = [];
		$('.selectedServices:checked').each(function () {
			arr[i++] = $(this).val();
		});
		$http({
			method: 'post',
			url: '../ajax/appauthorizeajax.php',
			data: {
				id: id,
				parentType: $scope.parentType,
				creditLimit: $scope.creditLimit,
				dailyLimit: $scope.dailyLimit,
				advanceAmount: $scope.advanceAmount,
				minimumBalance:$scope.minimumBalance,
				selectedServices: arr,
				comment: $scope.authorizeComment,
				action: 'authorize',
				partycatype: $scope.partycatype,
				type: $scope.type
			},
		}).then(function successCallback(response) {
			$("#ApproveBody").html("<h3 style='text-align:center'>" + response.data + "</h3>");
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});
app.controller('payReqCtrl', function ($scope, $http) {
	$scope.procharge =  (parseFloat(0)).toFixed(2) ;
	$scope.totalpaycom =  (parseFloat(0)).toFixed(2) ;
	$scope.isPayout = true;
	$scope.isHideResetS = true;
	$scope.isResDiv = true;
	$scope.isUpForm = false;
	$scope.isButtonDiv = false;
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'bank' }
	}).then(function successCallback(response) {
		$scope.banks = response.data;
		//window.location.reload();
	});
	$scope.partyload = function (partyType) {
		var action = "";var fora="";
		if(partyType == "MA") {
			fora = "agent";
			type = "N";
		}
		if(partyType == "SA") {
			fora = "agent";
			type = "Y";
		}
		if(partyType == "C") {
			fora = "champion";
			type = "";
		}
		if(partyType == "P") {
			fora = "personal";
			type = "";
		}
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:fora,
				    type: type
				},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});

	}
	$scope.cal= function () {
		$scope.totalpaycom =  (parseFloat($scope.paycomamt) + parseFloat($scope.procharge)).toFixed(2) ;
	}
	$scope.payout= function () {
		var curbalance =  parseInt($scope.curbalance) ;
		var totalpaycom =  parseInt($scope.totalpaycom) ;
		if(curbalance >totalpaycom) {
			$http({
				method: 'post',
				url: '../ajax/payreqajax.php',
				data: {
					partyType: $scope.partyType,
					partyCode: $scope.partyCode,
					creteria: $scope.creteria,
					curbalance: $scope.curbalance,
					paycomamt: $scope.paycomamt,
					procharge: $scope.procharge,
					totalpaycom: $scope.totalpaycom,
					action: 'payout',
					bankaccount: $scope.bankaccount
				},
			}).then(function successCallback(response) {
					$scope.ispayRequestForm = true;
					$scope.isResDiv = false;
					$scope.isUpForm = true;
					$scope.isButtonDiv = true;
					$scope.msg = response.data.msg;
					$scope.responseCode = response.data.responseCode;
					$scope.msg = response.data.msg;
					$scope.errorResponseDescription = response.data.errorResponseDescription;
			});
		}
		else {
			alert("Amount Should be valid");
		}
	}
	$scope.query = function () {
		$scope.curbalance = "";
		$http({
			method: 'post',
			url: '../ajax/payreqajax.php',
			data: {
				partyType: $scope.partyType,
				partyCode: $scope.partyCode,
				action: 'query'
			},
		}).then(function successCallback(response) {
				$scope.isPayout = false;
				$scope.isHideResetS = false;
				$scope.curbalance = response.data[0].curbal;
		});
	}
});
app.controller('payEntryCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.isHideReset = false;

	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'bank' }
	}).then(function successCallback(response) {
		$scope.banks = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'country' }
	}).then(function successCallback(response) {
		$scope.countrys = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'agent', "type": "N" }
	}).then(function successCallback(response) {
		$scope.agents = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'agent', "type": "Y" }
	}).then(function successCallback(response) {
		$scope.subagents = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'champion' }
	}).then(function successCallback(response) {
		$scope.champions = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'personal' }
	}).then(function successCallback(response) {
		$scope.personals = response.data;
		//window.location.reload();
	});
	$scope.paymentry = function () {
		$scope.isLoader = true;
		$http({
		method: 'post',
		url: '../ajax/payajax.php',
		data: {
			action: $scope.action,
			country: $scope.country,
			bankaccount: $scope.bankaccount,
			partytype: $scope.partytype,
			partycode: $scope.partycode,
			paymenttype: $scope.paymenttype,
			paymentdate: $scope.paymentdate,
			paymentamount: $scope.paymentamount,
			refdate: $scope.refdate,
			refno: $scope.refno,
			comment: $scope.comment,
			chequeno: $scope.chequeno,
			action: 'entry',
			creteria :$scope.creteria
		}

	}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isHideReset = true;
			$scope.isLoader = false;
			$("#PayentryCreateBody").html("<h3>" + response.data + "</h3>");
			//document.getElementById("paymentEntryForm").reset();
		});
	}
});
app.controller('payViewCtrl', function ($scope, $http) {
	$scope.isLoader = false;
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/payajax.php',
			data: {
				action: 'view',
				creteria: $scope.creteria,
				id: $scope.id,
				status: $scope.crestatus,
				paymentDate: $scope.paymentDate,
				approvedDate: $scope.approvedDate
			},
		}).then(function successCallback(response) {
			$scope.paymentviews = response.data;
		});
	}
	$scope.view = function (id) {
		$http({
			method: 'post',
			url: '../ajax/payajax.php',
			data: {
				action: 'detailview',
				id: id
			},
		}).then(function successCallback(response) {
			$scope.isHide = false;
			$scope.isHideOk = true;
			$scope.isLoader = false;
			$scope.isMainLoader = true;
			$scope.id = response.data[0].id;
			$scope.country = response.data[0].country;
			$scope.BankAccount = response.data[0].BankAccount;
			$scope.partyType = response.data[0].partyType;
			$scope.partyCode = response.data[0].partyCode;
			$scope.PaymentAmount = response.data[0].PaymentAmount;
			$scope.PaymentApprovedDate = response.data[0].PaymentApprovedDate;
			$scope.paymentType = response.data[0].paymentType;
			$scope.PaymentStatus = response.data[0].PaymentStatus;
			$scope.PaymentApprovedAmount = response.data[0].PaymentApprovedAmount;
			$scope.PaymentRefNo = response.data[0].PaymentRefNo;
			$scope.PaymentRefDate = response.data[0].PaymentRefDate;
			$scope.ChequeNo = response.data[0].ChequeNo;
			$scope.comment = response.data[0].comments;
			$scope.acomment = response.data[0].acomment;
			$scope.PaymentDate = response.data[0].PaymentDate;
		});
	}
});
app.controller('payApproveCtrl', function ($scope, $http) {
		$scope.paymentDate = new Date();
		$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/payajax.php',
			data: {
				action: 'payapprovesearch',
				creteria: $scope.creteria,
				type: $scope.partyType,
				paymentDate: $scope.paymentDate
			},
		}).then(function successCallback(response) {
			$scope.paymentapproves = response.data;
		});
	}
	$scope.view = function (index, id) {
			$http({
			method: 'post',
			url: '../ajax/payajax.php',
			data: {
				id: id,
				action: 'paymentapproveview'
			},
		}).then(function successCallback(response) {
			$scope.isHide = false;
			$scope.isHideOk = true;
			$scope.id = response.data[0].id;
			$scope.comment = response.data[0].comments;
			$scope.fuser = response.data[1].fuser;
			$scope.touser = response.data[1].tuser;
			$scope.partytype = response.data[0].type;
			$scope.partycode = response.data[0].code;
			$scope.paymentamount = response.data[0].payamount;
			$scope.paymentdate = response.data[0].paydate;
			$scope.paymentmode = response.data[0].paytype;
			$scope.status = response.data[0].status;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.approve = function (id, type, partycode) {
		   $scope.isLoader = true;
			$scope.isMainLoader = false;
		$http({
			method: 'post',
			url: '../ajax/payajax.php',
			data: {
				id: id,
				type: type,
				appcomment: $scope.approveComment,
				appppayamount: $scope.approvedamount,
				partycode: $scope.partycode,
				action: 'paymentapproveapprove'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#ApproveBody").html("<h3 style='text-align:center'>" + response.data + "</h3>");

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.reject = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/payajax.php',
			data: {
				id: id,
				action: 'paymentreject'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});
app.controller('adjEntryCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.isHideReset = false;
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'country' }
	}).then(function successCallback(response) {
		$scope.countrys = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'agent', "type": "N" }
	}).then(function successCallback(response) {
		$scope.agents = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'agent', "type": "Y" }
	}).then(function successCallback(response) {
		$scope.subagents = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'champion' }
	}).then(function successCallback(response) {
		$scope.champions = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'personal' }
	}).then(function successCallback(response) {
		$scope.personals = response.data;
		//window.location.reload();
	});

	$scope.adjustmentry = function () {
		$scope.isLoader = true;
		$http({
			method: 'post',
			url: '../ajax/adjajax.php',
			data: {
				country: $scope.country,
				partytype: $scope.partytype,
				partycode: $scope.partycode,
				adjustmenttype: $scope.adjustmenttype,
				adjustmentdate: $scope.adjustmentdate,
				adjustmentamount: $scope.adjustmentamount,
				refdate: $scope.refdate,
				refno: $scope.refno,
				comment: $scope.comment,
				action: 'entry'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isHideReset = true;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#adjentryCreateBody").html("<h3>" + response.data + "</h3>");
			//document.getElementById("adjustmentEntryForm").reset();
		});
	}
});
app.controller('adjViewCtrl', function ($scope, $http) {
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/adjajax.php',
			data: {
				action: 'view',
				creteria: $scope.creteria,
				id: $scope.id,
				status: $scope.crestatus,
				adjustmentDate: $scope.adjustmentDate,
				approvedDate: $scope.approvedDate
			},
		}).then(function successCallback(response) {
			$scope.adjustmentviews = response.data;
		});
	}
	$scope.view = function (id) {
		$scope.isLoader = true;
	$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/adjajax.php',
			data: {
				action: 'detailview',
				id: id
			},
		}).then(function successCallback(response) {
			$scope.id = response.data[0].id;
			$scope.country = response.data[0].country;
			$scope.date = response.data[0].date;
			$scope.partyCode = response.data[0].partyCode;
			$scope.partyType = response.data[0].partyType;
			$scope.adjustmentType = response.data[0].adjustmentType;
			$scope.adjustmentAmount = response.data[0].adjustmentAmount;
			$scope.adjustmentApprovedAmount = response.data[0].adjustmentApprovedAmount;
			$scope.adjustmentApprovedDate = response.data[0].adjustmentApprovedDate;
			$scope.adjustmentRefNo = response.data[0].adjustmentRefNo;
			$scope.adjustmentRefDate = response.data[0].adjustmentRefDate;
			$scope.adjustmentStatus = response.data[0].adjustmentStatus;
			$scope.comment = response.data[0].comment;
			$scope.acomment = response.data[0].acomment;
			$scope.isLoader = false;
	    $scope.isMainLoader = false;
		});
	}
});
app.controller('adjApproveCtrl', function ($scope, $http) {
	$scope.adjustmentDate = new Date();
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/adjajax.php',
			data: {
				action: 'adjapprovesearch',
				creteria: $scope.creteria,
				type: $scope.partyType,
				adjustmentDate: $scope.adjustmentDate
			},
		}).then(function successCallback(response) {
			$scope.adjustmentapproves = response.data;
		});
	}
	$scope.view = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/adjajax.php',
			data: {
				id: id,
				action: 'adjustmentapproveview'
			},
		}).then(function successCallback(response) {
			$scope.isHide = false;
			$scope.isHideOk = true;
			$scope.id = response.data[0].id;
			$scope.comment = response.data[0].comments;
			$scope.fuser = response.data[1].fuser;
			$scope.touser = response.data[1].tuser;
			$scope.partytype = response.data[0].type;
			$scope.partycode = response.data[0].code;
			$scope.adjustmentamount = response.data[0].adjamount;
			$scope.adjustmentdate = response.data[0].adjdate;
			$scope.adjustmentmode = response.data[0].adjtype;
			$scope.adjustmenttype = response.data[0].adjustmenttype;
			$scope.rtype = response.data[0].rtype,
				$scope.status = response.data[0].status;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.approve = function (id, type, partycode, adjustmenttype) {
		$http({
			method: 'post',
			url: '../ajax/adjajax.php',
			data: {
				id: id,
				type: type,
				appcomment: $scope.approveComment,
				apppadjamount: $scope.approvedamount,
				partycode: $scope.partycode,
				action: 'adjustmentapproveapprove',
				adjustmenttype: adjustmenttype,
				type: $scope.rtype
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#ApproveBody").html("<h3 style='text-align:center'>" + response.data + "</h3>");

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.reject = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/adjajax.php',
			data: {
				id: id,
				action: 'adjustmentreject'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});
app.controller('proCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/profileajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.profilelist = response.data;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'auth' }
	}).then(function successCallback(response) {
		$scope.auths = response.data;
		//window.location.reload();
	});
	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/profileajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.code = response.data[0].code;
			$scope.active = response.data[0].active;
			$scope.profiledesc = response.data[0].name;
			$scope.authorization = response.data[0].aid;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/profileajax.php',
			data: {
				id: $scope.id,
				code: $scope.code,
				active: $scope.active,
				profiledesc: $scope.profiledesc,
				authorization: $scope.authorization,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
		  $scope.isMainLoader = false;
			$("#ProfileCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
				}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isHideOk = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/profileajax.php',
			data: {
				id: $scope.id,
				code: $scope.code,
				active: $scope.active,
				authorization: $scope.authorization,
				profiledesc: $scope.profiledesc,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isLoader = false;
			$scope.isHideOk = false;
			$("#proBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('userCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/userajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.userList = response.data;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'profile' }
	}).then(function successCallback(response) {
		$scope.profiles = response.data;
		//window.location.reload();
	});
	$scope.edituser = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.fname = response.data[0].fname;
			$scope.lname = response.data[0].lname;
			$scope.active = response.data[0].active;
			$scope.email = response.data[0].email;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.usercheck = function ($event, userName) {
		$scope.loadgif = true;
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { userName: userName, action: 'usercheck' },
		}).then(function successCallback(response) {
			$scope.loadgif = true;
			if (response.data > 0) {
				$scope.colstyle = {
					"background-color": "red",
					"color": "white"
				}
				$('.check').html('<span style="position: absolute;"> <img src="../common/images/error.png" style="display:inline-block;margin-left:0px;"/>User Name is already taken.</span>');
				$scope.isHide = true;
				$scope.usrform = true;
			}
			else {
				$('.check').html('<span style="position: absolute;"> <img src="../common/images/accept.png" style="display:inline-block;margin-left:0px;"/>User Name is avaliable</span>');
				$scope.colstyle = {
					"background-color": "white"
				}
				$scope.isHide = false;
				$scope.usrform = false;
			}
		}, function errorCallback(response) {
			//console.log(response);
		});
	}
	$scope.createuser = function () {
		$scope.isHide = true;
		$scope.isHideReset = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;

		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: {
				action: 'insertsysuser',
				userName: $scope.userName,
				firstName: $scope.firstname,
				lastName: $scope.lastname,
				active: $scope.active,
				password: $scope.sysuserpassword,
				repassword: $scope.sysuserrepassword,
				startDate: $scope.startDate,
				endDate: $scope.endDate,
				email: $scope.email,
				profile: $scope.profile
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#UserCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
			//window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.editotpdetails = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'otpchange' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.otptype = response.data[0].odynamic;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.otytypeupdate = function (id) {
		$scope.isHide = true;
		$scope.isHideCancel = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: {
				id: id,
				action: 'otytypeupdate',
				otptype: $scope.otptype,
				sendmail: $scope.mail
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#OtpTypeBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.uppassreset = function (id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: {
				id: id,
				action: 'passreset',
				password: $scope.password,
				repassword: $scope.repassword
			},
		}).then(function successCallback(response) {
			alert(response.data)

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.editsotpdetails = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'scontrol' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.spin = response.data[0].pin;
			$scope.stotp = response.data[0].ovalue;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.regenerate = function (id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'regenerate' },
		}).then(function successCallback(response) {
			if (response.data[0].error == 0) {
				$scope.key = response.data[0].msg;
			}
			else {
				alert(response.data[0].msg);
				window.location.reload();
			}
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.editcontrol = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'control' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.locked = response.data[0].locked;
			$scope.ltime = response.data[0].ltime;
			$scope.id = response.data[0].id;
			$scope.fstlogin = response.data[0].fstlogin;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.changefstlogn = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'changefstlogn' },
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.changefstlogy= function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'changefstlogy' },
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.lock = function (id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'lock' },
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.unlock = function (id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'unlock' },
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.getcurrentotp = function (id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'getcurrentotp' },
		}).then(function successCallback(response) {
			$("#currmsg").html(response.data);

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.updatesotpvalue = function (id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: {
				id: id,
				otpvalue: $scope.stotp,
				action: 'ssotpupdate'
			},
		}).then(function successCallback(response) {
			alert(response.data);
			//window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.updatesotppin = function (id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: {
				id: id,
				pin: $scope.spin,
				action: 'pinupdate'
			},
		}).then(function successCallback(response) {
			alert(response.data);
			//window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.editdynamicotpdetails = function (index, id) {
		$http({
			method: 'post',
			url: '../common/qr.php',
			data: { id: id, action: 'editotpdetails' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.img = response.data.img;
			$scope.issuer = response.data[0].issuer;
			$scope.key = response.data[0].key;
			$scope.otptype = response.data[0].otptype;
			$scope.spin = response.data[0].pin;
			$scope.interval = response.data[0].interval;
			$scope.algorithm = response.data[0].algorithm;
			$scope.otplength = response.data[0].digits;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.sendmail = function (id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'sendmail' },
		}).then(function successCallback(response) {
			alert(response.data);
		}, function errorCallback(response) {
			// console.log(response);
		});
	}

	$scope.updateuser = function (id) {
		$scope.isHideOk = true;
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: {
				id: $scope.id,
				fname: $scope.fname,
				lname: $scope.lname,
				active: $scope.active,
				email: $scope.email,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#UserBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('countryCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/countryajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.countrylist = response.data;
	});
	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/countryajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.code = response.data[0].code;
			$scope.description = response.data[0].desc;
			$scope.active = response.data[0].active;
			$scope.dialcode = response.data[0].dialcode;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/countryajax.php',
			data: {
				code: $scope.code,
				desc: $scope.description,
				active: $scope.active,
				dialcode: $scope.dialcode,
				id: $scope.id,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isLoader = false;
		   $scope.isMainLoader = false;
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#CountryCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isHideOk = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/countryajax.php',
			data: {
				id: $scope.id,
				code: $scope.code,
				desc: $scope.description,
				active: $scope.active,
				dialcode: $scope.dialcode,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#CountryBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('appentryCtrl', function ($scope, $http) {

	$scope.isHideOk = true;
	$scope.isHideReset = false;
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'langs' }
	}).then(function successCallback(response) {
		$scope.langs = response.data;
		//window.location.reload();
	});
	$http({
		method: 'post',
		url: '../ajax/appentryajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.appentrylist = response.data;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'country' }
	}).then(function successCallback(response) {
		$scope.countrys = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'agent', "type": "N" }
	}).then(function successCallback(response) {
		$scope.agents = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'champion' }
	}).then(function successCallback(response) {
		$scope.champions = response.data;
		//window.location.reload();
	});
	$scope.create = function () {
		$scope.isLoader = true;
	  		$http({
			method: 'post',
			url: '../ajax/appentryajax.php',
			data: {
				id: $scope.id,
				category: $scope.category,
				country: $scope.country,
				outletname: $scope.outletname,
				taxnumber: $scope.taxnumber,
				localgovernment: $scope.localgovernment,
				address1: $scope.address1,
				address2: $scope.address2,
				state: $scope.state,
				zipcode: $scope.zipcode,
				mobileno: $scope.mobileno,
				workno: $scope.workno,
				email: $scope.email,
				cname: $scope.cname,
				cmobile: $scope.cmobile,
				comment: $scope.comment,
				appliertype: $scope.appliertype,
				parentcode: $scope.parentcode,
				langpref:$scope.langpref,
				action: 'create'

			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isHideReset = true;
			$scope.isLoader = false;
         	$("#AppentryCreateBody").html("<h3>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.countrychange = function (id) {
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'statelist', "id": id, "action": "active" },
		}).then(function successCallback(response) {
			$scope.states = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}

	$scope.statechange = function (id) {
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'localgvtlist', "id": id, "action": "active" },
		}).then(function successCallback(response) {
			$scope.localgvts = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});
app.controller('appViewCtrl', function ($scope, $http) {
	$scope.isLoader = true;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.isMainLoader = true;
	$scope.isHideOk = true;
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/appviewajax.php',
			data: {
				id: $scope.id,
				crestatus: $scope.crestatus,
				startDate: $scope.startDate,
				endDate: $scope.endDate,
				creteria: $scope.creteria,
				action: 'query'
			},
		}).then(function successCallback(response) {
			//	$scope.isHide = true;
			//	$scope.isHideOk = false;
			$scope.isLoader = false;
	     $scope.isMainLoader = false;
			$scope.appviews = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.print = function (index, id) {

		$http({
			method: 'post',
			url: '../ajax/appviewajax.php',
			data: {
				id: id,
				action: 'view'
			},
		}).then(function successCallback(response) {
			//	$scope.isHide = true;
			//	$scope.isHideOk = false;
			var creteria = $scope.creteria;
			var id = $scope.id;
			var statusa = $scope.statusa;
			var startDate = $scope.startDate;
			var endDate = $scope.endDate;
			var text = "";
			var valu = "";
			if (creteria == "BI") {
				text = "By Id";
				valu = id;
			}
			if (creteria == "BS") {
				text = "By Status";
				valu = statusa;
			}
			if (creteria == "BD") {
				text = "By Date";
				valu = "From: " + startDate + " to " + endDate;
			}
			var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
				'<style>' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/logo/logo.jpg" width="100px" height="40px"/>' +
				'<h2 style="text-align:center;margin-top:30px">Application View Report - ' + response.data[0].outletname + '</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
			var response = "<p>Search Creteria For: </p>" + text + " - " + valu +
				"<table class='table table-bordered'><tbody>" +
				"<tr><th>Application #</th><th>" + response.data[0].id + "</th></tr>" +
				"<tr><th>Category</th><th>" + response.data[0].category + "</th></tr>" +
				"<tr><th>Country</th><th>" + response.data[0].country + "</th></tr>" +
				"<tr><th>Outlet Name</th><th>" + response.data[0].outletname + "</th></tr>" +
				"<tr><th>Applier Type</th><th>" + response.data[0].type + "</th></tr>" +
				"<tr><th>Party Code</th><th>" + response.data[0].partyc + "</th></tr>" +
				"<tr><th>Parent Code</th><th>" + response.data[0].parentc + "</th></tr>" +
				"<tr><th>Create Time</th><th>" + response.data[0].time + "</th></tr>" +
				"<tr><th>Status</th><th>" + response.data[0].statusa + "</th></tr>" +
				"<tr><th>Address 1</th><th>" + response.data[0].address1 + "</th></tr>" +
				"<tr><th>Address 2</th><th>" + response.data[0].address2 + "</th></tr>" +
				"<tr><th>Local Govt.</th><th>" + response.data[0].localgovt + "</th></tr>" +
				"<tr><th>State</th><th>" + response.data[0].state + "</th></tr>" +
				"<tr><th>Zip Code</th><th>" + response.data[0].zip + "</th></tr>" +
				"<tr><th>Tax Number</th><th>" + response.data[0].tax + "</th></tr>" +
				"<tr><th>E-Mail</th><th>" + response.data[0].email + "</th></tr>" +
				"<tr><th>Mobile</th><th>" + response.data[0].mobile + "</th></tr>" +
				"<tr><th>Work No</th><th>" + response.data[0].work + "</th></tr>" +
				"<tr><th>Contact Person Name</th><th>" + response.data[0].cpn + "</th></tr>" +
				"<tr><th>Contact Person Mobile</th><th>" + response.data[0].cpm + "</th></tr>" +
				"<tr><th>Approve Comments</th><th>" + response.data[0].apcomment + "</th></tr>" +
				"<tr><th>Approve Time</th><th>" + response.data[0].aptime + "</th></tr>" +
				"<tr><th>Authorize Comments</th><th>" + response.data[0].aucomment + "</th></tr>" +
				"<tr><th>Authorize Time</th><th>" + response.data[0].autime + "</th></tr>" +
				"<tr><th>Comments</th><th>" + response.data[0].comments + "</th></tr>" +
				"<tr><th>Account Setup</th><th>" + response.data[0].asetup + "</th></tr>" +
				"<tr><th>User Setup</th><th>" + response.data[0].usetup + "</th></tr>" +
				"<tr><th>Login Name</th><th>" + response.data[0].login + "</th></tr>" +
				"</tbody></table>";
			var win = window.open("", "height=1000", "width=1000");
			with (win.document) {
				open();
				write(img + response + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
				close();
			}
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.view = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/appviewajax.php',
			data: {
				id: id,
				action: 'view'
			},
		}).then(function successCallback(response) {
			//	$scope.isHide = true;
			//	$scope.isHideOk = false;
			$scope.id = response.data[0].id;
			$scope.category = response.data[0].category;
			$scope.country = response.data[0].country;
			$scope.outletname = response.data[0].outletname;
			$scope.type = response.data[0].type;
			$scope.parentc = response.data[0].pcode;
			$scope.partyc = response.data[0].partyc;
			$scope.lang = response.data[0].lang;
			$scope.cdate = response.data[0].time;
			$scope.statusa = response.data[0].statusa;
			$scope.address1 = response.data[0].address1;
			$scope.address2 = response.data[0].address2;
			$scope.localgovt = response.data[0].localgovt;
			$scope.state = response.data[0].state;
			$scope.zipcode = response.data[0].zip;
			$scope.taxnumber = response.data[0].tax;
			$scope.email = response.data[0].email;
			$scope.mobile = response.data[0].mobile;
			$scope.workno = response.data[0].work;
			$scope.cname = response.data[0].cpn;
			$scope.cmobile = response.data[0].cpm;
			$scope.aptime = response.data[0].aptime;
			$scope.apcomment = response.data[0].apcomment;
			$scope.autime = response.data[0].autime;
			$scope.aucomment = response.data[0].aucomment;
			$scope.comments = response.data[0].comments;
			$scope.asetup = response.data[0].asetup;
			$scope.usetup = response.data[0].usetup;
			$scope.login = response.data[0].login;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});

app.controller('stateCtrl', function ($scope, $http) {
	$scope.isHideOk = true;

	$http({
		method: 'post',
		url: '../ajax/stateajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.statelist = response.data;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'country' }
	}).then(function successCallback(response) {
		$scope.countrys = response.data;
		//window.location.reload();
	});

	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/stateajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.name = response.data[0].name;
			$scope.country = response.data[0].country;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
			$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/stateajax.php',
			data: {
				id: $scope.id,
				active: $scope.active,
				name: $scope.name,
				country: $scope.country,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#stateCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isLoader = true;
			$scope.isMainLoader = true;
		$scope.isHideOk = true;
		$http({
			method: 'post',
			url: '../ajax/stateajax.php',
			data: {
				id: $scope.id,
				name: $scope.name,
				active: $scope.active,
				country: $scope.country,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#countryBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}

});
app.controller('localCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/localgovtajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.localgvtlist = response.data;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'statelistall' }
	}).then(function successCallback(response) {
		$scope.statelist = response.data;
		//window.location.reload();
	});

	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/localgovtajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.name = response.data[0].name;
			$scope.stateedit = response.data[0].state;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/localgovtajax.php',
			data: {
				id: $scope.id,
				active: $scope.active,
				name: $scope.name,
				state: $scope.createstate,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#localGovtCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$scope.isHideOk = true;
		$http({
			method: 'post',
			url: '../ajax/localgovtajax.php',
			data: {
				id: $scope.id,
				name: $scope.name,
				active: $scope.active,
				state: $scope.stateedit,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
	     	$scope.isMainLoader = false;
			$("#locALgOVERMENTBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}

});
app.controller('flexiCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.mtn = function () {
		$scope.code  = "MTN";
	}
	$scope.recharge = function () {
		$http({
			method: 'post',
			url: '../ajax/flexirechargeajax.php',
			data: {
				mobile: $scope.mobile,
				lineType: $scope.lineType,
				reMobile: $scope.remobile,
				operatorCode: $scope.code,
				amount: $scope.amount
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$(".flexiOperatorBody").html("<h3>"+response.data+"</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
	$scope.atl = function () {
		$scope.code  = "ATL";
	}
	$scope.glo = function () {
		$scope.code  = "GLO";
	}
	$scope.eti = function () {
		$scope.code  = "9M";
	}
});
app.controller('finCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'finproducts' }
	}).then(function successCallback(response) {
		$scope.productlist = response.data;
		//window.location.reload();
	});
	$scope.getcharge = function () {
		$http({
			method: 'post',
			url: '../ajax/getchargeajax.php',
			data: {
				reqamount: $scope.reqamount,
				product: $scope.product,
				action: 'chargefind'
			},
		}).then(function successCallback(response) {
			$scope.sercharge = response.data[0].scharge;
			$scope.procharge = response.data[0].pcharge;
			$scope.othercharge = response.data[0].ocharge;
			$scope.total = response.data[0].total;
			$scope.fincode = response.data[0].fncode;

		}, function errorCallback(response) {
			console.log(response);
		});
		//console.log("ream"+$scope.reqamount);
	}
	$scope.finorder = function (fincode) {
		$http({
			method: 'post',
			url: '../ajax/finserviceorderajax.php',
			data: {
				finCode: fincode,
				total: $scope.total,
				reqAmount: $scope.reqamount,
				product: $scope.product,
				scharge: $scope.sercharge,
				ocharge: $scope.othercharge,
				pcharge: $scope.procharge,
				customer: $scope.customer,
				authorization: $scope.authorization,
				mobile: $scope.mobile,
				ref: $scope.ref,
				comment: $scope.comment,
				action: 'chargefind'
			},
		}).then(function successCallback(response) {
			alert(response.data);
		}, function errorCallback(response) {
			console.log(response.data);
		});
		//console.log("ream"+$scope.reqamount);
	}
});
app.controller('passwordChgCtrl', function ($scope, $http) {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$scope.isHideOk = true;
	$scope.change = function () {
		$http({
			method: 'post',
			url: '../ajax/passchangeajax.php',
			data: {
				username: $scope.username,
				passwordtype: $scope.passwordtype,
				curpassword: $scope.curpassword,
				newpassword: $scope.newpassword,
				renewpassword: $scope.renewpassword
			},
		}).then(function successCallback(response) {
			$scope.isHideOk = false;
			$("#passChangeBody").html("<h3>" + response.data + "</h3>");
		}, function errorCallback(response) {
			console.log(response);
		});
		//console.log("ream"+$scope.reqamount);
	}
});
app.controller('commCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.fn_load = function (partyType,partyCode) {
		if(partyType == 'C' || partyType == 'A') {
			$http({
				method: 'post',
				url: '../ajax/load.php',
				params: { partyType:partyType,
						partyCode:partyCode,
						action: 'infolist'
				},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});
		}
	}
	$scope.partyload = function (partyType) {
		var action = "";var fora="";
		if(partyType == "MA") {
			fora = "agent";
			type = "N";
		}
		if(partyType == "SA") {
			fora = "agent";
			type = "Y";
		}
		if(partyType == "C") {
			fora = "champion";
			type = "";
		}
		if(partyType == "P") {
			fora = "personal";
			type = "";
		}
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:fora,
				    type: type
				},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});

	}
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/commajax.php',
			data: {
				action: 'findlist',
				partyCode: $scope.partyCode,
				partyType: $scope.partyType,
				topartyCode:$scope.topartyCode,
				creteria:$scope.creteria
			},
		}).then(function successCallback(response) {
			$scope.comms = response.data;
		}, function errorCallback(response) {
			console.log(response.data);
		});
	}
	$scope.edit = function (index, code, type,creteria) {
		$http({
			method: 'post',
			url: '../ajax/commajax.php',
			data: { code: code,type: type, action: 'edit',creteria:creteria },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.application_id = response.data[0].application_id;
			$scope.block_date = response.data[0].block_date;
			$scope.block_reason_id = response.data[0].block_reason_id;
			$scope.block_status = response.data[0].block_status;
			$scope.code = response.data[0].code;
			$scope.contact_person_mobile = response.data[0].contact_person_mobile;
			$scope.contact_person_name = response.data[0].contact_person_name;
			$scope.country = response.data[0].country;
			$scope.create_time = response.data[0].create_time;
			$scope.create_user = response.data[0].create_user;
			$scope.email = response.data[0].email;
			$scope.expiry_date = response.data[0].expiry_date;
			$scope.gvtname = response.data[0].gvtname;
			$scope.lname = response.data[0].lname;
			$scope.mobile_no = response.data[0].mobile_no;
			$scope.name = response.data[0].name;
			$scope.outlet_name = response.data[0].outlet_name;
			$scope.parenroutletname = response.data[0].parenroutletname;
			$scope.partytype = response.data[0].partytype;
			$scope.pcode = response.data[0].pcode;
			$scope.ptype = response.data[0].ptype;
			$scope.start_date = response.data[0].start_date;
			$scope.state = response.data[0].state;
			$scope.sub_agent = response.data[0].sub_agent;
			$scope.tax_number = response.data[0].tax_number;
			$scope.update_time = response.data[0].update_time;
			$scope.update_user = response.data[0].update_user;
			$scope.user = response.data[0].user;
			$scope.work_no = response.data[0].work_no;
			$scope.zip_code = response.data[0].zip_code;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}

});
app.controller('infoCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.fn_load = function (partyType,partyCode) {
		if(partyType == 'C' || partyType == 'A') {
			$http({
				method: 'post',
				url: '../ajax/load.php',
				params: { partyType:partyType,
						partyCode:partyCode,
						action: 'infolist'
				},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});
		}
	}
	$scope.partyload = function (partyType) {
		var action = "";var fora="";
		if(partyType == "MA") {
			fora = "agent";
			type = "N";
		}
		if(partyType == "SA") {
			fora = "agent";
			type = "Y";
		}
		if(partyType == "C") {
			fora = "champion";
			type = "";
		}
		if(partyType == "P") {
			fora = "personal";
			type = "";
		}
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:fora,
				    type: type
				},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});

	}
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/infoajax.php',
			data: {
				action: 'findlist',
				partyCode: $scope.partyCode,
				partyType: $scope.partyType,
				topartyCode:$scope.topartyCode,
				creteria:$scope.creteria
			},
		}).then(function successCallback(response) {
			$scope.infoss = response.data;
		}, function errorCallback(response) {
			console.log(response.data);
		});
	}
	$scope.edit = function (index, code, type, creteria) {
		$http({
			method: 'post',
			url: '../ajax/infoajax.php',
			data: { code: code,type: type, action: 'edit',creteria:creteria },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.application_id = response.data[0].application_id;
			$scope.block_date = response.data[0].block_date;
			$scope.block_reason_id = response.data[0].block_reason_id;
			$scope.block_status = response.data[0].block_status;
			$scope.code = response.data[0].code;
			$scope.contact_person_mobile = response.data[0].contact_person_mobile;
			$scope.contact_person_name = response.data[0].contact_person_name;
			$scope.country = response.data[0].country;
			$scope.create_time = response.data[0].create_time;
			$scope.create_user = response.data[0].create_user;
			$scope.email = response.data[0].email;
			$scope.expiry_date = response.data[0].expiry_date;
			$scope.gvtname = response.data[0].gvtname;
			$scope.lname = response.data[0].lname;
			$scope.mobile_no = response.data[0].mobile_no;
			$scope.name = response.data[0].name;
			$scope.outlet_name = response.data[0].outlet_name;
			$scope.parenroutletname = response.data[0].parenroutletname;
			$scope.partytype = response.data[0].partytype;
			$scope.pcode = response.data[0].pcode;
			$scope.ptype = response.data[0].ptype;
			$scope.start_date = response.data[0].start_date;
			$scope.state = response.data[0].state;
			$scope.sub_agent = response.data[0].sub_agent;
			$scope.tax_number = response.data[0].tax_number;
			$scope.update_time = response.data[0].update_time;
			$scope.update_user = response.data[0].update_user;
			$scope.user = response.data[0].user;
			$scope.work_no = response.data[0].work_no;
			$scope.zip_code = response.data[0].zip_code;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (code) {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$scope.isHideOk = true;
		$http({
			method: 'post',
			url: '../ajax/infoajax.php',
			data: {
				mobile: $scope.mobile_no,
				email: $scope.email,
				cpname: $scope.contact_person_name,
				cpmobile: $scope.contact_person_mobile,
				partyCode: code,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
	     	$scope.isMainLoader = false;
			$("#infoBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('walletCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.fn_load = function (partyType,partyCode) {
		if(partyType == 'C' || partyType == 'A') {
			$http({
				method: 'post',
				url: '../ajax/load.php',
				params: { partyType:partyType,
						partyCode:partyCode,
						action: 'infolist'
				},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});
		}
	}
	$scope.partyload = function (partyType) {
		var action = "";var fora="";
		if(partyType == "MA") {
			fora = "agent";
			type = "N";
		}
		if(partyType == "SA") {
			fora = "agent";
			type = "Y";
		}
		if(partyType == "C") {
			fora = "champion";
			type = "";
		}
		if(partyType == "P") {
			fora = "personal";
			type = "";
		}
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:fora,
				    type: type
				},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});

	}
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/walletajax.php',
			data: {
				action: 'findlist',
				partyCode: $scope.partyCode,
				partyType: $scope.partyType,
				topartyCode:$scope.topartyCode,
				creteria:$scope.creteria
			},
		}).then(function successCallback(response) {
			$scope.infoss = response.data;
		}, function errorCallback(response) {
			console.log(response.data);
		});
	}
	$scope.edit = function (index, code, type, creteria) {
		$http({
			method: 'post',
			url: '../ajax/walletajax.php',
			data: { code: code,type: type, action: 'edit',creteria:creteria },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.advance_amount = response.data[0].advance_amount;
			$scope.atype = response.data[0].atype;
			$scope.available_balance = response.data[0].available_balance;
			$scope.block_date = response.data[0].block_date;
			$scope.block_reason_id = response.data[0].block_reason_id;
			$scope.block_status = response.data[0].block_status;
			$scope.code = response.data[0].code;
			$scope.create_time = response.data[0].create_time;
			$scope.create_user = response.data[0].create_user;
			$scope.credit_limit = response.data[0].credit_limit;
			$scope.current_balance = response.data[0].current_balance;
			$scope.daily_limit = response.data[0].daily_limit;
			$scope.last_tx_amount = response.data[0].last_tx_amount;
			$scope.last_tx_date = response.data[0].last_tx_date;
			$scope.last_tx_no = response.data[0].last_tx_no;
			$scope.lname = response.data[0].lname;
			$scope.minimum_balance = response.data[0].minimum_balance;
			$scope.name = response.data[0].name;
			$scope.pcode = response.data[0].pcode;
			$scope.parenrtoutletname = response.data[0].parenrtoutletname;
			$scope.previous_current_balance = response.data[0].previous_current_balance;
			$scope.ptype = response.data[0].ptype;
			$scope.sub_agent = response.data[0].sub_agent;
			$scope.outlet_name = response.data[0].outlet_name;
			$scope.uncleared_balance = response.data[0].uncleared_balance;
			$scope.update_user = response.data[0].update_user;
			$scope.update_time = response.data[0].update_time;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});
app.controller('blckreasonCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/blckreasonajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.blockreasonlist = response.data;
	});
	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/blckreasonajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.code = response.data[0].code;
			$scope.description = response.data[0].desc;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/blckreasonajax.php',
			data: {
				code: $scope.code,
				desc: $scope.description,
				id: $scope.id,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isLoader = false;
		   $scope.isMainLoader = false;
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#BlckreasonCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isHideOk = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/blckreasonajax.php',
			data: {
				id: $scope.id,
				code: $scope.code,
				desc: $scope.description,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#BlckreasonBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('treInfoCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.fn_load = function (partyType,partyCode) {
		if(partyType == 'C' || partyType == 'A') {
			$http({
				method: 'post',
				url: '../ajax/load.php',
				params: { partyType:partyType,
						partyCode:partyCode,
						action: 'infolist'
				},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});
		}
	}
	$scope.partyload = function (partyType) {
		var action = "";var fora="";
		if(partyType == "MA") {
			fora = "agent";
			type = "N";
		}
		if(partyType == "SA") {
			fora = "agent";
			type = "Y";
		}
		if(partyType == "C") {
			fora = "champion";
			type = "";
		}
		if(partyType == "P") {
			fora = "personal";
			type = "";
		}
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:fora,
				    type: type
				},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});

	}
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/infotreajax.php',
			data: {
				action: 'findlist',
				partyCode: $scope.partyCode,
				partyType: $scope.partyType,
				topartyCode:$scope.topartyCode,
				creteria:$scope.creteria
			},
		}).then(function successCallback(response) {
			$scope.infoss = response.data;
		}, function errorCallback(response) {
			console.log(response.data);
		});
	}
	$scope.edit = function (index, code, type) {
		$http({
			method: 'post',
			url: '../ajax/infotreajax.php',
			data: { code: code,type: type, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.application_id = response.data[0].application_id;
			$scope.block_date = response.data[0].block_date;
			$scope.block_reason_id = response.data[0].block_reason_id;
			$scope.block_status = response.data[0].block_status;
			$scope.code = response.data[0].code;
			$scope.contact_person_mobile = response.data[0].contact_person_mobile;
			$scope.contact_person_name = response.data[0].contact_person_name;
			$scope.country = response.data[0].country;
			$scope.create_time = response.data[0].create_time;
			$scope.create_user = response.data[0].create_user;
			$scope.email = response.data[0].email;
			$scope.expiry_date = response.data[0].expiry_date;
			$scope.gvtname = response.data[0].gvtname;
			$scope.lname = response.data[0].lname;
			$scope.mobile_no = response.data[0].mobile_no;
			$scope.name = response.data[0].name;
			$scope.outlet_name = response.data[0].outlet_name;
			$scope.parenroutletname = response.data[0].parenroutletname;
			$scope.partytype = response.data[0].partytype;
			$scope.pcode = response.data[0].pcode;
			$scope.ptype = response.data[0].ptype;
			$scope.start_date = response.data[0].start_date;
			$scope.state = response.data[0].state;
			$scope.sub_agent = response.data[0].sub_agent;
			$scope.tax_number = response.data[0].tax_number;
			$scope.update_time = response.data[0].update_time;
			$scope.update_user = response.data[0].update_user;
			$scope.user = response.data[0].user;
			$scope.work_no = response.data[0].work_no;
			$scope.zip_code = response.data[0].zip_code;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (code) {
		$scope.isHideOk = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/infotreajax.php',
			data: {
				blckstatus: $scope.block_status,
				code: $scope.code,
				blckreason: $scope.block_reason_id,
				active: $scope.active,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#treatmentInfOBody").html("<h3>" + response.data + "</h3>");
		}, function errorCallback(response) {
			console.log(response);
		});
	}

	$scope.detail = function (index, code, type) {
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:'blkreson'
				},
			}).then(function successCallback(response) {
				$scope.blckreson = response.data;
			});
		$http({
			method: 'post',
			url: '../ajax/infotreajax.php',
			data: { code: code,type: type, action: 'detail' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.application_id = response.data[0].application_id;
			$scope.block_date = response.data[0].block_date;
			$scope.block_reason_id = response.data[0].block_reason_id;
			$scope.block_status = response.data[0].block_status;
			$scope.code = response.data[0].code;
			$scope.contact_person_mobile = response.data[0].contact_person_mobile;
			$scope.contact_person_name = response.data[0].contact_person_name;
			$scope.country = response.data[0].country;
			$scope.create_time = response.data[0].create_time;
			$scope.create_user = response.data[0].create_user;
			$scope.email = response.data[0].email;
			$scope.expiry_date = response.data[0].expiry_date;
			$scope.gvtname = response.data[0].gvtname;
			$scope.lname = response.data[0].lname;
			$scope.mobile_no = response.data[0].mobile_no;
			$scope.name = response.data[0].name;
			$scope.outlet_name = response.data[0].outlet_name;
			$scope.parenroutletname = response.data[0].parenroutletname;
			$scope.partytype = response.data[0].partytype;
			$scope.pcode = response.data[0].pcode;
			$scope.ptype = response.data[0].ptype;
			$scope.start_date = response.data[0].start_date;
			$scope.state = response.data[0].state;
			$scope.sub_agent = response.data[0].sub_agent;
			$scope.tax_number = response.data[0].tax_number;
			$scope.update_time = response.data[0].update_time;
			$scope.update_user = response.data[0].update_user;
			$scope.user = response.data[0].user;
			$scope.work_no = response.data[0].work_no;
			$scope.zip_code = response.data[0].zip_code;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});
app.controller('treWallCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.fn_load = function (partyType,partyCode) {
		if(partyType == 'C' || partyType == 'A') {
			$http({
				method: 'post',
				url: '../ajax/load.php',
				params: { partyType:partyType,
						partyCode:partyCode,
						action: 'infolist'
				},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});
		}
	}
	$scope.partyload = function (partyType) {
		var action = "";var fora="";
		if(partyType == "MA") {
			fora = "agent";
			type = "N";
		}
		if(partyType == "SA") {
			fora = "agent";
			type = "Y";
		}
		if(partyType == "C") {
			fora = "champion";
			type = "";
		}
		if(partyType == "P") {
			fora = "personal";
			type = "";
		}
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:fora,
				    type: type
				},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});

	}
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/walltreajax.php',
			data: {
				action: 'findlist',
				partyCode: $scope.partyCode,
				partyType: $scope.partyType,
				topartyCode:$scope.topartyCode,
				creteria:$scope.creteria
			},
		}).then(function successCallback(response) {
			$scope.infoss = response.data;
		}, function errorCallback(response) {
			console.log(response.data);
		});
	}
	$scope.edit = function (index, code, type) {
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:'blkreson'
				},
			}).then(function successCallback(response) {
				$scope.blckreson = response.data;
			});
		$http({
			method: 'post',
			url: '../ajax/walltreajax.php',
			data: { code: code,type: type, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.advance_amount = response.data[0].advance_amount;
			$scope.atype = response.data[0].atype;
			$scope.available_balance = response.data[0].available_balance;
			$scope.code = response.data[0].code;
			$scope.create_time = response.data[0].create_time;
			$scope.create_user = response.data[0].create_user;
			$scope.credit_limit = response.data[0].credit_limit;
			$scope.current_balance = response.data[0].current_balance;
			$scope.daily_limit = response.data[0].daily_limit;
			$scope.last_tx_amount = response.data[0].last_tx_amount;
			$scope.last_tx_date = response.data[0].last_tx_date;
			$scope.last_tx_no = response.data[0].last_tx_no;
			$scope.lname = response.data[0].lname;
			$scope.minimum_balance = response.data[0].minimum_balance;
			$scope.name = response.data[0].name;
			$scope.pcode = response.data[0].pcode;
			$scope.parenroutletname = response.data[0].parenroutletname;
			$scope.previous_current_balance = response.data[0].previous_current_balance;
			$scope.ptype = response.data[0].ptype;
			$scope.sub_agent = response.data[0].sub_agent;
			$scope.outlet_name = response.data[0].outlet_name;
			$scope.uncleared_balance = response.data[0].uncleared_balance;
			$scope.update_user = response.data[0].update_user;
			$scope.update_time = response.data[0].update_time;
			$scope.active = response.data[0].active;
			$scope.blckreason = response.data[0].block_reason_id;
			$scope.blckstatus = response.data[0].block_status;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (code) {
		$scope.isHideOk = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/walltreajax.php',
			data: {
				blckstatus: $scope.blckstatus,
				code: $scope.code,
				blckreason: $scope.blckreason,
				active: $scope.active,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#treatmentInfOBody").html("<h3>" + response.data + "</h3>");
		}, function errorCallback(response) {
			console.log(response);
		});
	}

	$scope.detail = function (index, code, type) {
		$http({
			method: 'post',
			url: '../ajax/walltreajax.php',
			data: { code: code,type: type, action: 'detail' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.advance_amount = response.data[0].advance_amount;
			$scope.atype = response.data[0].atype;
			$scope.available_balance = response.data[0].available_balance;
			$scope.block_date = response.data[0].block_date;
			$scope.block_reason_id = response.data[0].block_reason_id;
			$scope.block_status = response.data[0].block_status;
			$scope.code = response.data[0].code;
			$scope.create_time = response.data[0].create_time;
			$scope.create_user = response.data[0].create_user;
			$scope.credit_limit = response.data[0].credit_limit;
			$scope.current_balance = response.data[0].current_balance;
			$scope.daily_limit = response.data[0].daily_limit;
			$scope.last_tx_amount = response.data[0].last_tx_amount;
			$scope.last_tx_date = response.data[0].last_tx_date;
			$scope.last_tx_no = response.data[0].last_tx_no;
			$scope.lname = response.data[0].lname;
			$scope.minimum_balance = response.data[0].minimum_balance;
			$scope.name = response.data[0].name;
			$scope.pcode = response.data[0].pcode;
			$scope.parenroutletname = response.data[0].parenroutletname;
			$scope.previous_current_balance = response.data[0].previous_current_balance;
			$scope.ptype = response.data[0].ptype;
			$scope.sub_agent = response.data[0].sub_agent;
			$scope.outlet_name = response.data[0].outlet_name;
			$scope.uncleared_balance = response.data[0].uncleared_balance;
			$scope.update_user = response.data[0].update_user;
			$scope.update_time = response.data[0].update_time;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});
app.controller('posaccCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:'user',action:'active'
				},
			}).then(function successCallback(response) {
				$scope.users = response.data;
			});
	$http({
		method: 'post',
		url: '../ajax/posaccajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.userposlist = response.data;
	});
	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/posaccajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.id = response.data[0].id;
			$scope.name = response.data[0].name;
			$scope.active = response.data[0].active;
			$scope.imei = response.data[0].imei;
			$scope.status = response.data[0].status;
			$scope.pin = response.data[0].pin;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;

		$http({
			method: 'post',
			url: '../ajax/posaccajax.php',
			data: {
				name: $scope.name,
				active: $scope.active,
				imei: $scope.imei,
				status: $scope.status,
				pin: $scope.pin,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isLoader = false;
		   $scope.isMainLoader = false;
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#PosaccCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isHideOk = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/posaccajax.php',
			data: {
				id:id,
				name: $scope.name,
				active: $scope.active,
				imei: $scope.imei,
				status: $scope.status,
				pin: $scope.pin,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#PosaccBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('posaccactCtrl', function ($scope, $http) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.isHideOk = true;
	$scope.checkdate = function (startDate,endDate){
		var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
		var currdate = new Date();
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
			$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
			alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
		}
		else {
			$scope.isQueryDi = false;
		}
	}
	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:'userpos',action:'active'
				},
			}).then(function successCallback(response) {
				$scope.users = response.data;
			});
	$scope.query = function () {
		var startDate =  $scope.startDate;
		var endDate =  $scope.endDate;
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		var currdate = new Date();
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
			$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
			alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
		}
		else {
			$http({
				method: 'post',
				url: '../ajax/posaccactajax.php',
				data: {
					action: 'list',
					user: $scope.user,
					startDate: $scope.startDate,
					endDate: $scope.endDate
				},
			}).then(function successCallback(response) {
					$scope.posacts = response.data;
			});
		}
	}
});
app.controller('parCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/partajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.partnerlist = response.data;
	});
	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:'partnertype',action:'active'
				},
			}).then(function successCallback(response) {
				$scope.partnertype = response.data;
		});
	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:'bankmasters',action:'active'
				},
			}).then(function successCallback(response) {
				$scope.bankmasterss = response.data;
		});
	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'statelistall',"action": "active" },
		}).then(function successCallback(response) {
			$scope.states = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'country' }
		}).then(function successCallback(response) {
				$scope.countrys = response.data;
		//window.location.reload();
		});
	$scope.countrychange = function (id) {
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'statelist', "id": id, "action": "active" },
		}).then(function successCallback(response) {
			$scope.states = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}

	$scope.statechange = function (id) {
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'localgvtlist', "id": id, "action": "active" },
		}).then(function successCallback(response) {
			$scope.localgvts = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/partajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.id = response.data[0].partner_id;
			$scope.end_date = response.data[0].end_date;
			$scope.partner_address = response.data[0].partner_address;
			$scope.partner_country_id = response.data[0].partner_country_id;
			$scope.partner_local_govt_id = response.data[0].partner_local_govt_id;
			$scope.partner_name = response.data[0].partner_name;
			$scope.partner_state_id = response.data[0].partner_state_id;
			$scope.partner_type_id = response.data[0].partner_type_id;
			$scope.start_date = response.data[0].start_date;
			$scope.bankmaster = response.data[0].bank_master_id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/partajax.php',
			data: {
				active: $scope.active,
				end_date: $scope.end_date,
				partner_address: $scope.partner_address,
				partner_country_id: $scope.partner_country_id,
				partner_local_govt_id: $scope.partner_local_govt_id,
				partner_name: $scope.partner_name,
				partner_state_id: $scope.partner_state_id,
				partner_type_id: $scope.partner_type_id,
				start_date: $scope.start_date,
				bankmaster: $scope.bankmaster,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isLoader = false;
		   $scope.isMainLoader = false;
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#PartnerCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isHideOk = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/partajax.php',
			data: {
				id: id,
				active: $scope.active,
				end_date: $scope.end_date,
				partner_address: $scope.partner_address,
				partner_country_id: $scope.partner_country_id,
				partner_local_govt_id: $scope.partner_local_govt_id,
				partner_name: $scope.partner_name,
				partner_state_id: $scope.partner_state_id,
				partner_type_id: $scope.partner_type_id,
				start_date: $scope.start_date,
				bankmaster: $scope.bankmaster,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#PartnerBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('parTypeCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/parttypeajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.partnertypelist = response.data;
	});

	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/parttypeajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.partner_type_name = response.data[0].ams_partner_type_name;
			$scope.id = response.data[0].id;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/parttypeajax.php',
			data: {
				active: $scope.active,
				partner_type_name: $scope.partner_type_name,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isLoader = false;
		   $scope.isMainLoader = false;
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#PartnerTypeCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isHideOk = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/parttypeajax.php',
			data: {
				id: id,
				active: $scope.active,
				partner_type_name: $scope.partner_type_name,
				action: 'update'			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#PartnerTypeBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});

app.controller('servfeatCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/servfeatajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.servicefeaturelist = response.data;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'servfeat' }
	}).then(function successCallback(response) {
		$scope.servfeat = response.data;
		//window.location.reload();
	});

	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/servfeatajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.code = response.data[0].code;
			$scope.description = response.data[0].desc;
			$scope.active = response.data[0].active;
			$scope.servicetype = response.data[0].typeid;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/servfeatajax.php',
			data: {
				code: $scope.code,
				desc: $scope.description,
				active: $scope.active,
				typeid: $scope.servicetype,
				id: $scope.id,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
		  $scope.isMainLoader = false;
			$("#ServfeatCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isHideOk = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/servfeatajax.php',
			data: {
				id: $scope.id,
				code: $scope.code,
				desc: $scope.description,
				active: $scope.active,
				typeid: $scope.servicetype,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isLoader = false;
			$scope.isHideOk = false;
			$("#ServfeatBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('passwrdCtrl', function ($scope, $http) {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$scope.isHideOk = true;
	$scope.change = function () {
		   $http({
			method: 'post',
			url: '../ajax/passwrdajax.php',
			data: {
				username: $scope.username,
				passwordtype: $scope.passwordtype,
				newpassword: $scope.newpassword,
				renewpassword: $scope.renewpassword
			},
		}).then(function successCallback(response) {
            $scope.isHide = true;
			$scope.isHideOk = false;
			$("#passwrdBody").html("<h3>" + response.data + "</h3>");
		}, function errorCallback(response) {
			console.log(response);
		});
		//console.log("ream"+$scope.reqamount);
	}
});
app.controller('partycatypeCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/parcattyajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.parcttylis = response.data;
	});

	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/parcattyajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.name = response.data[0].name;
			$scope.id = response.data[0].id;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/parcattyajax.php',
			data: {
				active: $scope.active,
				name: $scope.name,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isLoader = false;
		   $scope.isMainLoader = false;
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#PartycatypeCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isHideOk = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/parcattyajax.php',
			data: {
				id: id,
				active: $scope.active,
				name: $scope.name,
				action: 'update'			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#partycatypeBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});

app.controller('serChargGrpCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/servchagrpajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.servichgrplist = response.data;
	});
		$http({
				method: 'post',
				url: '../ajax/load.php',
				params: { for: 'statelistall',"action": "active" },
			}).then(function successCallback(response) {
				$scope.states = response.data;
			}, function errorCallback(response) {
				// console.log(response);
			});
		$http({
			url: '../ajax/load.php',
			method: "POST",
			//Content-Type: 'application/json',
			params: { action: 'active', for: 'country' }
			}).then(function successCallback(response) {
					$scope.countrys = response.data;
			//window.location.reload();
			});
		$http({
			url: '../ajax/load.php',
			method: "POST",
			//Content-Type: 'application/json',
			params: { action: 'active', for: 'servfea' }
			}).then(function successCallback(response) {
					$scope.servfeas = response.data;
			//window.location.reload();
			});
	$scope.countrychange = function (id) {
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'statelist', "id": id, "action": "active" },
		}).then(function successCallback(response) {
			$scope.states = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}

	$scope.statechange = function (id) {
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'localgvtlist', "id": id, "action": "active" },
		}).then(function successCallback(response) {
			$scope.localgvts = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.edit = function (index, id) {
		$http({
				method: 'post',
				url: '../ajax/load.php',
				params: { for: 'statelistall',"action": "active" },
			}).then(function successCallback(response) {
				$scope.states = response.data;
			}, function errorCallback(response) {
				// console.log(response);
			});
		$http({
			url: '../ajax/load.php',
			method: "POST",
			//Content-Type: 'application/json',
			params: { action: 'active', for: 'country' }
			}).then(function successCallback(response) {
					$scope.countrys = response.data;
			//window.location.reload();
			});
		$http({
			url: '../ajax/load.php',
			method: "POST",
			//Content-Type: 'application/json',
			params: { action: 'active', for: 'servfea' }
			}).then(function successCallback(response) {
					$scope.servfeas = response.data;
			//window.location.reload();
			});
			$http({
				method: 'post',
				url: '../ajax/servchagrpajax.php',
				data: { id: id, action: 'edit' },
			}).then(function successCallback(response) {
				$http({
						method: 'post',
						url: '../ajax/load.php',
						params: { for: 'localgvtlist', "id": response.data[0].state, "action": "active" },
					}).then(function successCallback(response) {
						$scope.localgvts = response.data;
					}, function errorCallback(response) {
						// console.log(response);
					});

				$scope.active = response.data[0].active;
				$scope.id = response.data[0].id;
				$scope.locgvt = response.data[0].locgvt;
				$scope.name = response.data[0].name;
				$scope.country = response.data[0].country;
				$scope.pcount = response.data[0].pcount;
				$scope.serfea = response.data[0].serfea;
				$scope.state = response.data[0].state;

			}, function errorCallback(response) {
				// console.log(response);
			});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;

		$http({
			method: 'post',
			url: '../ajax/servchagrpajax.php',
			data: {
				active: $scope.active,
				locgvt: $scope.locgvt,
				name: $scope.name,
				country: $scope.country,
				pcount: $scope.pcount,
				serfea: $scope.serfea,
				state: $scope.state,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isLoader = false;
		   $scope.isMainLoader = false;
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#serCharGrpCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isHideOk = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/servchagrpajax.php',
			data: {
				id: id,
				active: $scope.active,
				locgvt: $scope.locgvt,
				name: $scope.name,
				country: $scope.country,
				pcount: $scope.pcount,
				serfea: $scope.serfea,
				state: $scope.state,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#serCharGrpBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('serCharRatCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.isHideReset = false;
	$http({
		method: 'post',
		url: '../ajax/sercharateajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.servichratelist = response.data;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'serchargrp' }
		}).then(function successCallback(response) {
				$scope.serchargrps = response.data;
		//window.location.reload();
		});
		$scope.save = function () {
			var i = 0;
			var j = 0;
			var k = 0;
			var l = 0;
			var m = 0;
			var n = 0;
			var o = 0;
			var arr = [];
			var raf = [];rate
			var catty = [];
			var rate = [];
			var active = [];
			var sdate = [];
			var edate = [];
			$('.partName').each(function () {
				if($(this).val() > 0 || $(this).val() != "") {
					arr[i++] = $(this).val();
				}
			});
			$('.partName').each(function () {
				if($(this).val() > 0 || $(this).val() != "") {
					arr[i++] = $(this).val();
				}
			});
			$('.rateFactor').each(function () {
				if($(this).val() != "") {
					raf[j++] = $(this).val();
				}
			});
			$('.cattype').each(function () {
				if($(this).val() != "") {
					catty[k++] = $(this).val();
				}
			});
			$('.rateva').each(function () {
				if($(this).val() != "") {
					rate[l++] = $(this).val();
				}
			});
			$('.active').each(function () {
				if($(this).val() != "") {
					active[m++] = $(this).val();
				}
			});
			$('.sdate').each(function () {
				if($(this).val() != "") {
					sdate[n++] = $(this).val();
				}
			});
			$('.edate').each(function () {
				if($(this).val() != "") {
					edate[o++] = $(this).val();
				}
			});
			$http({
				url: '../ajax/sercharateajax.php',
				method: "POST",
				//Content-Type: 'application/json',
				data: {
					action:'save',
					partyName: arr,
					rateFac: raf,
					catType: catty,
					rateVl: rate,
					active:active,
					sdate:sdate,
					edate:edate,
					grpname:$scope.grpname
					},
				}).then(function successCallback(response) {
					$scope.isHideOk = false;
					$scope.isHide = true;
					$("#serCharRatForm").html("<h3>"+response.data+"</h3>");
				//window.location.reload();
			});
		}
	$scope.deleter = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/sercharateajax.php',
			data: { id: id, action: 'delete' },
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.sergrpchange = function (id) {
		$http({
			method: 'post',
			url: '../ajax/sercharateajax.php',
			data: {
					action: 'parctforserchrat',
					id: id
			},
		}).then(function successCallback(response) {
			var responsehtml = "<table class='table table-condensed'><thead><tr><th>Party</th><th>Rate Factor</th><th>Category Type</th><th>Rate Value</th><th>Active</th><th>Start Date</th><th>End Date</th></tr></thead><tbody>";
			var responsegen= "";
			var optionres= "";
			var partycatres= "";
			//alert(response.data[0].count);
			$http({
				url: '../ajax/load.php',
				method: "POST",
				//Content-Type: 'application/json',
				params: { action: 'active', for: 'sercharpar' }
				}).then(function successCallback(response2) {
					$http({
						url: '../ajax/load.php',
						method: "POST",
						//Content-Type: 'application/json',
						params: { action: 'active', for: 'partycatype' }
						}).then(function successCallback(response3) {
							 for(var i=0;i < response3.data.length;i++) {
								partycatres +=  "<option value='"+response3.data[i].id+"'>"+response3.data[i].name+"</option>";
							}

						//window.location.reload();
						 for(var i=0;i < response2.data.length;i++) {
								optionres +=  "<option value='"+response2.data[i].id+"'>"+response2.data[i].name+"</option>";
							}
							for(var i =0;i < response.data[0].count;i++) {
								responsegen += "<tr><td><select ng-model='partyName["+i+"]' class='form-control partName' name = 'partyName["+i+"]' id='partyName' required>"+
													"<option value=''>--Select Party--</option>"+
														optionres+
													"</select></td><td><select ng-model='rateFactor[]' name='rateFactopr' class='form-control rateFactor'><option value=''>--Select Rate Factor--</option><option value='A'>Amount</option><option value='P'>Percentage</option></select></td>"+
													"<td><select ng-model='partyCats[]' class='form-control  cattype' name = 'partyCats' id='partyCats' required>"+
													"<option value=''>--Select Party Category--</option>"+
														partycatres+
													"</select></td>"+
													"<td><input type='number' name='rateval[]' ng-model='rateval[]' class='rateva form-control' /></td>"+
													"<td><select ng-model='active[]' name='active' class='form-control active'><option value=''>--Select Active--</option><option value='Y'>Yes</option><option value='N'>No</option></select></td>"+
													"<td><input type='date' name='satrtdate[]' ng-model='satrtdate[]' class='sdate form-control' /></td>"+
													"<td><input type='date' name='endDate[]' ng-model='endDate[]' class='edate form-control' /></td>"+

													"</tr>";
							}
						responseend = responsehtml +  responsegen+"</tbody></table>";
						console.log(responseend);
						$("#reee").html(responseend);
				});

		}, function errorCallback(response) {
			// console.log(response);
		});
		});
	}
});
app.controller('serFetConfCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.isHideReset = false;
	$http({
		method: 'post',
		url: '../ajax/serfetconfajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.servichconlist = response.data;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'servfea' }
		}).then(function successCallback(response) {
				$scope.servfeas = response.data;
		//window.location.reload();
		});
		$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'partners' }
		}).then(function successCallback(response) {
				$scope.partners = response.data;
		//window.location.reload();
		});
		$scope.create = function () {
			$scope.isLoader = true;
			$scope.isMainLoader = true;

			$http({
				method: 'post',
				url: '../ajax/serfetconfajax.php',
				data: {
					chfa: $scope.chfa,
					chval: $scope.chval,
					partner: $scope.partner,
					patxtype: $scope.patxtype,
					pchfa: $scope.pchfa,
					pchval: $scope.pchval,
					ochfa: $scope.ochfa,
					ochval: $scope.ochval,
					action: 'create',
					serfea:$scope.serfea,
					sfstval:$scope.sfstval,
					sfenva:$scope.sfenva
				},
			}).then(function successCallback(response) {
				$scope.isLoader = false;
				$scope.isMainLoader = false;
				$scope.isHide = true;
				$scope.isHideOk = false;
				$("#servFetConfCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/serfetconfajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.serfea = response.data[0].fid;
			$scope.active = response.data[0].active;
			$scope.sfstval = response.data[0].svalue;
			$scope.sfenva = response.data[0].evalue;
			$scope.chfa = response.data[0].acf;
			$scope.chval = response.data[0].acv;
			$scope.partner = response.data[0].pid;
			$scope.patxtype = response.data[0].ptx;
			$scope.pchfa = response.data[0].pcf;
			$scope.pchval = response.data[0].pcv;
			$scope.ochfa = response.data[0].ocf;
			$scope.ochval = response.data[0].ocv;
			$scope.id = response.data[0].id;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isHideOk = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/serfetconfajax.php',
			data: {
				id: id,
				chfa: $scope.chfa,
				active: $scope.active,
				chval: $scope.chval,
				partner: $scope.partner,
				patxtype: $scope.patxtype,
				pchfa: $scope.pchfa,
				pchval: $scope.pchval,
				ochfa: $scope.ochfa,
				ochval: $scope.ochval,
				action: 'create',
				serfea:$scope.serfea,
				sfstval:$scope.sfstval,
				sfenva:$scope.sfenva,
				action: 'update'			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#servFetConfEditBody").html("<h3>" + response.data + "</h3>");
		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('pBankCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/pbankaccajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.banklist = response.data;
	});

	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:'bankmasters',action:'active'
				},
			}).then(function successCallback(response) {
				$scope.bankmasterss = response.data;
		});

	$scope.partyload = function (partyType) {
		var action = "";var fora="";
		if(partyType == "MA") {
			fora = "agent";
			type = "N";
		}
		if(partyType == "SA") {
			fora = "agent";
			type = "Y";
		}
		if(partyType == "C") {
			fora = "champion";
			type = "";
		}
		if(partyType == "P") {
			fora = "personal";
			type = "";
		}
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:fora,
				    type: type
				},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});

	}
	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/pbankaccajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.id = response.data[0].id;
			$scope.partyType = response.data[0].ptype;
			$scope.partyCode = response.data[0].pcode;
			$scope.bankmaster = response.data[0].bankmaster;
			$scope.accno = response.data[0].accno;
			$scope.reaccno = response.data[0].accno;
			$scope.accname = response.data[0].accname;
			$scope.bankaddress = response.data[0].bankaddress;
			$scope.bankbranch = response.data[0].bankbranch;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/pbankaccajax.php',
			data: {
				active: $scope.active,
				partyType: $scope.partyType,
				partyCode: $scope.partyCode,
				bankmaster: $scope.bankmaster,
				accname: $scope.accname,
				accno: $scope.accno,
				reaccno: $scope.reaccno,
				bankaddress: $scope.bankaddress,
				bankbranch: $scope.bankbranch,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isLoader = false;
		   $scope.isMainLoader = false;
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#PBankAccountCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isHideOk = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/pbankaccajax.php',
			data: {
				active: $scope.active,
				bankmaster: $scope.bankmaster,
				accname: $scope.accname,
				accno: $scope.accno,
				reaccno: $scope.reaccno,
				bankaddress: $scope.bankaddress,
				bankbranch: $scope.bankbranch,
				action: 'update',
				id:id
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#PBankAccountEditBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('serCharParCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.isHideReset = false;
	$http({
		method: 'post',
		url: '../ajax/sercharpartyajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.sechpalist = response.data;
	});

	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/sercharpartyajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.name = response.data[0].name;
			$scope.id = response.data[0].id;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/sercharpartyajax.php',
			data: {
				active: $scope.active,
				name: $scope.name,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isLoader = false;
		   $scope.isMainLoader = false;
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#serviceChargePartyCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isHideOk = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/sercharpartyajax.php',
			data: {
				id: id,
				active: $scope.active,
				name: $scope.name,
				action: 'update'			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#serviceChargePartyBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('finCashInCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.payDisable = true;
	$scope.fianceServieOrdeForm = true;
	$scope.payRefresh = true;
	$scope.isProcess = false;
	$scope.isOk = true;
	$scope.isMainDiv = true;
	$http({
		url: '../ajax/load.php',
		method: "POST",
		params: { action: 'active', for: 'servfeaforpro', code: 'CIN' }
	}).then(function successCallback(response) {
		$scope.finser = response.data;
		$scope.isMainDiv = false;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		params: { action: 'active', for: 'partners' }
		}).then(function successCallback(response) {
			$scope.partners = response.data;
			$scope.isMainDiv = false;
		});
	$scope.calculate = function (product, partner, txtype, reqamount) {
		$scope.bankname =  $('#partner option:selected').attr('lab');
		$scope.fianceServieOrdeForm = false;
		$scope.isMainDiv = true;
		$http({
		url: '../ajax/fincashinajax.php',
		method: "POST",
		data: {
			product: product,
			partner: partner,
			txtype: txtype,
			reqamount: reqamount,
			action: 'calculate'
			},
		}).then(function successCallback(response) {
			$scope.fianceServieOrdeForm = false;
			$scope.isMainDiv = false;
			var split2 =response.data.split("#");
			var serconfig =split2[1];
			var split3 =split2[0].split("|");
			var split4 =split2[1].split(",");
		    var j=0;
			var respotable = "<table class='table table-bordered'><thead><tr><th>Charge Rate Id</th><th>Charge Party</th><th>User Id</th><th>User Name</th></tr></thead><tbody>";
			for(var i=0;i<split4.length;i++) {
				respotable += "<tr><td>"+ split4[i].split('~')[j]+"</td><td>"+ split4[i].split('~')[j+1]+"</td><td>"+ split4[i].split('~')[j+2] +"</td><td>"+ split4[i].split('~')[j+3] +"</td></tr>";

			}
			respotable += "</tbody></table>";
			var check = split3[0];
			if(check >= 0) {
				$scope.payDisable = false;
				$scope.payRefresh = false;
				$scope.isHide = true;
				$scope.amscharge = split3[2];
				$scope.parcharge = split3[3];
				$scope.othcharge = split3[4];
				$scope.serconf = serconfig;
				$scope.sedeco = split3[1];
				$scope.totalcharge =  parseFloat(parseFloat(split3[4]) +  parseFloat(split3[3]) +  parseFloat(split3[2])).toFixed(2) ;
				$scope.total =  (parseFloat(reqamount) + parseFloat($scope.totalcharge)).toFixed(2) ;
				$("#tableres").html(respotable);
			}
			else {
				alert("Error in getting charges..Contact Kadick");
				$scope.payDisable = true;
				$scope.payRefresh = true;
			}
		});
	}
	$scope.reset = function () {
		//document.getElementById('fianceServieOrdeForm').reset();
		$scope.fianceServieOrdeForm.$dirty = true;
		$scope.fianceServieOrdeForm.$invalid = true;
		$scope.isHide = false;
		$scope.payDisable = true;
		$scope.cashInForm.$dirty = true;
		$scope.cashInForm.$invalid = true;
		$scope.amscharge = "";
		$scope.total = "";
		$scope.parcharge = "";
		$scope.othcharge = "";
		$scope.totalcharge = "";
		$scope.serconf = "";
		$scope.sedeco = "";
		$("#vali").hide();
		$scope.mobile = "0";
	}
	$scope.pay = function () {
		$("#payBody").html("<h3>Your Cash In Order for amount NGN "+$scope.reqamount+" is in Progress. Please Wait..!</h3>");
		$scope.isLoader = true;
		$scope.isCloTime = true;
		$scope.isProcess = true;
		$http({
		url: '../ajax/fincashinajax.php',
		method: "POST",
		//Content-Type: 'application/json',
			data: {
				card: $scope.card,
				exdate: $scope.exdate,
				cvc: $scope.cvc,
				cardname: $scope.cardname,
				partner:$scope.partner,
				reqamount:$scope.reqamount,
				sedeco:$scope.sedeco,
				action: 'pay',
				accountno: $scope.accountno,
				reaccountno: $scope.reaccountno,
				name:  $scope.name,
				narration: $scope.comment,
				mobile: $scope.mobile,
				reqamount: $scope.reqamount,
				amscharge:$scope.amscharge,
				parcharge:$scope.parcharge,
				product:$scope.product,
				othcharge:$scope.othcharge,
				totalcharge:$scope.totalcharge,
				totalAmount:$scope.total,
				serconfig:$scope.serconf,
				txtype:$scope.txtype
			},
		}).then(function successCallback(response) {
			$("#payBody").html("<h3>"+response.data.responseDescription+"</h3>");
			$scope.isHideOk = false;
			$scope.isHide = true;
			$scope.isHideReset = true;
			$scope.isProcess = true;
			$scope.isOk = false;
		})
	}
});
app.controller('finCashOutCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.payDisable = true;
	$scope.payRefresh = true;
	$scope.isProcess = false;
	$scope.fianceServieOrdeForm = true;
	$scope.isGetCancelButtonDiv = true;
	$scope.isOk = true;
	$scope.isMainDiv = true;
	$scope.isPayDiv = true;
	$scope.isGetOtpButtonDiv = false;
	$scope.isPayButtonDiv = true;
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'servfeaforpro', code: 'COU' }
	}).then(function successCallback(response) {
		$scope.finser = response.data;
		$scope.isMainDiv = false;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'partners' }
		}).then(function successCallback(response) {
			$scope.partners = response.data;
			$scope.isMainDiv = false;
		});
	$scope.calculate = function (product, partner, txtype, reqamount) {
		$scope.isMainDiv = true;
		$scope.fianceServieOrdeForm = true;
		$scope.fianceServieOrdeForm = false;
		$scope.bankname =  $('#partner option:selected').attr('lab');

		$http({
		url: '../ajax/fincashoutajax.php',
		method: "POST",
		//Content-Type: 'application/json',
			data: {
				product: product,
				partner: partner,
				txtype: txtype,
				reqamount: reqamount,
				action: 'calculate'
			},
		}).then(function successCallback(response) {
			$scope.isMainDiv = false;
			$scope.fianceServieOrdeForm = false;
			var split2 =response.data.split("#");
			var serconfig =split2[1];
			var split3 =split2[0].split("|");
			var split4 =split2[1].split(",");
		    var j=0;
			var respotable = "<table class='table table-bordered'><thead><tr><th>Charge Rate Id</th><th>Charge Party</th><th>User Id</th><th>User Name</th></tr></thead><tbody>";
			for(var i=0;i<split4.length;i++) {
				respotable += "<tr><td>"+ split4[i].split('~')[j]+"</td><td>"+ split4[i].split('~')[j+1]+"</td><td>"+ split4[i].split('~')[j+2] +"</td><td>"+ split4[i].split('~')[j+3] +"</td></tr>";

			}
			respotable += "</tbody></table>";
			var check = split3[0];
			if(check >= 0) {
				$scope.payDisable = false;
				$scope.payRefresh = false;
				$scope.isHide = true;
				$scope.amscharge = split3[2];
				$scope.parcharge = split3[3];
				$scope.othcharge = split3[4];
				$scope.serconf = serconfig;
				$scope.sedeco = split3[1];
				$scope.totalcharge =  parseFloat(parseFloat(split3[4]) +  parseFloat(split3[3]) +  parseFloat(split3[2])).toFixed(2) ;
				$scope.total =  (parseFloat(reqamount) + parseFloat($scope.totalcharge)).toFixed(2) ;
				$("#tableres").html(respotable);
			}
			else {
				alert("Error in getting charges..Contact Kadick");
				$scope.payDisable = true;
				$scope.payRefresh = true;
			}
		//window.location.reload();
		});
	}
	$scope.procced = function () {
		$scope.isLoader = true;
		$scope.isCloTime = true;
		$scope.isProcess = true;
		$scope.isGetCancelButtonDiv = true;
		$scope.isGetOtpDiv = false;
		$scope.isPayButtonDiv = false;
				$scope.isFormSuccessDiv = false;
				$scope.isGetOtpButtonDiv = true;
				$scope.isProcess = false;
				$scope.isGetCancelButtonDiv = true;
				$scope.isFormFailedDiv	 = true;
				$scope.isGetOtpDiv = true;
		/*$http({
		url: '../ajax/fincashoutajax.php',
		method: "POST",
		//Content-Type: 'application/json',
			data: {
				partnerId: $scope.partner,
				accountNo: $scope.accountno,
				amount:$scope.total,
				mobileNumber: $scope.mobile,
				action: 'getotp'
			},
		}).then(function successCallback(response) {
			$scope.isGetOtpDiv = true;
			//response.data.responseCode = -1;
			if(response.data.responseCode == 0) {
				$scope.isPayButtonDiv = false;
				$scope.isFormSuccessDiv = false;
				$scope.isGetOtpButtonDiv = true;
				$scope.isProcess = false;
				$scope.isGetCancelButtonDiv = true;
				$scope.isFormFailedDiv	 = true;
			}
			else {
				$scope.isGetOtpButtonDiv = true;
				$scope.isGetCancelButtonDiv = false;
				$scope.resDesc = response.data.responseDescription;
				$scope.isFormSuccessDiv = true;
				$scope.isFormFailedDiv	 = false;
			}
		}) */
	}
	$scope.reset = function () {
		//document.getElementById('fianceServieOrdeForm').reset();
		$scope.isHide = false;
		$scope.fianceServieOrdeForm.$dirty = true;
		$scope.fianceServieOrdeForm.$invalid = true;
		$scope.payDisable = true;
		$scope.cashInForm.$dirty = true;
		$scope.cashInForm.$invalid = true;
		$scope.amscharge = "";
		$scope.total = "";
		$scope.parcharge = "";
		$scope.mobile = 0;
		$scope.othcharge = "";
		$scope.totalcharge = "";
		$scope.serconf = "";
		$scope.sedeco = "";
		$("#vali").hide();

	}
	$scope.pay = function () {
		$("#payBody").html("<h3>Your Cash Out Order for amount NGN "+$scope.reqamount+" is in Progress. Please Wait..!</h3>");
		$scope.isLoader = true;
		$scope.isCloTime = true;
		$scope.isProcess = true;
		$scope.isGetOtpDiv = false;
		$http({
		url: '../ajax/fincashoutajax.php',
		method: "POST",
		//Content-Type: 'application/json',
			data: {
				card: $scope.card,
				exdate: $scope.exdate,
				cvc: $scope.cvc,
				cardname: $scope.cardname,
				partner:$scope.partner,
				reqamount:$scope.reqamount,
				sedeco:$scope.sedeco,
				action: 'pay',
				accountno: $scope.accountno,
				reaccountno: $scope.reaccountno,
				name:  $scope.name,
				narration: $scope.comment,
				mobile: $scope.mobile,
				reqamount: $scope.reqamount,
				amscharge:$scope.amscharge,
				parcharge:$scope.parcharge,
				product:$scope.product,
				othcharge:$scope.othcharge,
				totalcharge:$scope.totalcharge,
				totalAmount:$scope.total,
				serconfig:$scope.serconf,
				txtype:$scope.txtype,
				otp:$scope.otp
			},
		}).then(function successCallback(response) {
			$("#payBody").html("<h3>"+response.data.responseDescription+"</h3>");
			$scope.isHideOk = false;
			$scope.isHide = true;
			$scope.isHideReset = true;
			$scope.isProcess = true;
			$scope.isOk = false;
			scope.isGetOtpDiv = false;
		})
	}

});
app.controller('accBalCtrl', function ($scope, $http) {
	 $scope.tabeHide = true;
	 $scope.tabeHide2 = true;
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'bankaccts' }
		}).then(function successCallback(response) {
			$scope.bankaccts = response.data;
		});
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/accbalajax.php',
			data: {
				action: 'getbalenq',
				accountNo: $scope.accno

			},
		}).then(function successCallback(response) {
			$scope.resc = response.data.responseCode;
			//alert($scope.resc);
			if(parseInt($scope.resc) == 0) {
				$scope.accname = response.data.accountName;
				$scope.accountNo = response.data.accountNo;
				$scope.avabal = response.data.availableBalance;
				$scope.currbal = response.data.currentBalance;
				$scope.rescode = response.data.responseCode;
				$scope.resdesc = response.data.responseDescription;
				$scope.prostart = response.data.processingStartTime;
				$scope.tabeHide = false;
				$scope.tabeHide2 = true;
			}
			else {
				$scope.tabeHide2 = false;
				$scope.tabeHide = true;
				$scope.rescode = response.data.responseCode;
				$scope.resdesc = response.data.responseDescription;
				$scope.prostart = response.data.processingStartTime;
			}
			//console.log(response.data);
		});
	}
});
app.controller('traEnCtrl', function ($scope, $http) {
	 $scope.tabeHide = true;
	 $scope.tabeHide2 = true;
    $http({
        url: '../ajax/load.php',
        method: "POST",
        //Content-Type: 'application/json',
        params: { action: 'active', for: 'partners' }
        }).then(function successCallback(response) {
            $scope.partners = response.data;
            $scope.isMainDiv = false;
    });
    $scope.query = function () {
        $http({
            method: 'post',
            url: '../ajax/transactionenajax.php',
            data: {
                action: 'gettransaction',
                refNo: $scope.refno,
                partner: $scope.partner
            },
        }).then(function successCallback(response) {
			$scope.resc = response.data.responseCode;
			//alert($scope.resc);
			if(parseInt($scope.resc) == 0) {
				$scope.txid = response.data.transactionId;
				$scope.txdate = response.data.transactionDate;
				$scope.creacc = response.data.creditAccount;
				$scope.debacc = response.data.debitAccount;
				$scope.txdesc = response.data.transDescription;
				$scope.operation = response.data.operation;
				$scope.refno = response.data.transactionRef;
				$scope.isreversed = response.data.isReversed;
				$scope.reversaldate = response.data.reversalDate;
				$scope.agencycode = response.data.agencyCode;
				$scope.agencyrequestid = response.data.agentRequestId;
				$scope.amount = response.data.amount;
				$scope.status = response.data.status;
				$scope.rescode = response.data.responseCode;
				$scope.resdesc = response.data.responseDescription;
				$scope.prostart = response.data.processingStartTime;
				$scope.tabeHide = false;
				$scope.tabeHide2 = true;
			}
			else {
				$scope.tabeHide2 = false;
				$scope.tabeHide = true;
				$scope.rescode = response.data.responseCode;
				$scope.resdesc = response.data.responseDescription;
				$scope.prostart = response.data.processingStartTime;
			}
            //console.log(response.data);
        });
    }
});
app.controller('bvnCtrl', function ($scope, $http) {
	$scope.tabeHide = true;
	$scope.tabeHide2 = true;
    $http({
        url: '../ajax/load.php',
        method: "POST",
        //Content-Type: 'application/json',
        params: { action: 'active', for: 'partners' }
        }).then(function successCallback(response) {
            $scope.partners = response.data;
            $scope.isMainDiv = false;
    });
	$scope.query = function () {
        $http({
            method: 'post',
            url: '../ajax/bvnajax.php',
            data: {
                action: 'getbvn',
                bvn: $scope.bvn,
                partner: $scope.partner
            },
        }).then(function successCallback(response) {
			$scope.resc = response.data.responseCode;
			//alert($scope.resc);
			if(parseInt($scope.resc) == 0) {
				$scope.tabeHide = false;
				$scope.tabeHide2 = true;
				$scope.fname = response.data.firstName;
				$scope.mname = response.data.middleName;
				$scope.lname = response.data.lastname;
				$scope.enbank = response.data.enrolmentBank;
				$scope.mobile = response.data.mobileNumber;
				$scope.dob = response.data.dateOfBirth;
				$scope.redate = response.data.registrationDate;
				$scope.timeout = response.data.isTimeout;
				$scope.rescode = response.data.responseCode;
				$scope.resdesc = response.data.responseDescription;
				$scope.result = response.data.result;
				$scope.loggerid = response.data.loggerID;
				$scope.hastoken = response.data.hasToken;
				$scope.adddata = response.data.additionalData;
				$scope.prostart = response.data.processingStartTime;
			}
			else {
				$scope.tabeHide2 = false;
				$scope.tabeHide = true;
				$scope.rescode = response.data.responseCode;
				$scope.resdesc = response.data.responseDescription;
				$scope.prostart = response.data.processingStartTime;
			}
        });
    }
});
app.controller('agStatReportCtrl', function ($scope, $http, $filter) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.orderdetail = true;
	$scope.tablerow = true;
	$http({
		method: 'post',
		url: '../ajax/load.php',
		params: { for: 'servfeaforcode',action:'active' },
	}).then(function successCallback(response) {
		$scope.types = response.data;
	}, function errorCallback(response) {
		// console.log(response);
	});
	$http({
		method: 'post',
		url: '../ajax/load.php',
		params: { for: 'subforagent',action:'active' },
	}).then(function successCallback(response) {
		$scope.subagents = response.data;
	}, function errorCallback(response) {
		// console.log(response);
	});
	$scope.impor =function () {
     $scope.tablerow = false;
}
	$scope.reset = function () {
		$scope.tablerow = false;
		$scope.startDate = new Date();
		$scope.endDate = new Date();
		$scope.orderdetail = true;
		$scope.agentdetail = false;
		$scope.subagentdetail = false;
		$scope.subAgentName = "ALL";
		$scope.type = "ALL";
		$scope.ba = 'ra';
	}

	$scope.query = function () {
		$scope.tablerow = true;
		var startDate =  $scope.startDate;
		var endDate =  $scope.endDate;
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		var currdate = new Date();
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
			$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
			alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
		}
		else {
			$http({
				method: 'post',
				url: '../ajax/agstatereportajax.php',
				data: {
					action: 'getreport',
					type: $scope.type,
					agentName: $scope.agentName,
					subAgentName:$scope.subAgentName,
					agentDetail: $scope.agentDetail,
					subAgentDetail:$scope.subAgentDetail,
					typeDetail: $scope.orderdetail,
					startDate: $scope.startDate,
					endDate: $scope.endDate,
					creteria: $scope.creteria
				},
			}).then(function successCallback(response) {
				$scope.res = response.data;
				$scope.td = response.data[0].td;
				$scope.ad =response.data[0].ad;
				$scope.sd =response.data[0].sd;
				$scope.mssage = response.data[0].mssage;
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
	}
});

app.controller('jEntryCtrl', function ($scope, $http, $filter) {
	$scope.isHideOk = true;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.fn_load = function (partyType,partyCode) {
	if(partyType == 'C' || partyType == 'A') {
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params:
				{ partyType:partyType,
				partyCode:partyCode,
				action: 'infolist'
			},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});
		}
	}
	$scope.partyload = function (partyType) {
		var action = "";var fora="";
		if(partyType == "MA") {
			fora = "agent";
			type = "N";
		}
		if(partyType == "SA") {
			fora = "agent";
			type = "Y";
		}
		if(partyType == "C") {
			fora = "champion";
			type = "";
		}
		if(partyType == "P") {
			fora = "personal";
			type = "";
		}
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:fora,
			   type: type
				},
		}).then(function successCallback(response) {
			$scope.infos = response.data;
		});
	}
		$scope.checkdate = function (startDate,endDate){
			var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
			var currdate = new Date();
			var difference  = new Date(endDate - startDate);
			var diffInDays  = difference/1000/60/60/24;
			if(endDate > currdate) {
				alert("End Date can't be more than current Date");
				$scope.endDate = currdate;
			//$scope.isQueryDi = true;
			}
			else if(startDate > endDate){
				$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
			}
			else if(diffInDays>7) {
				alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
			}
			else {
				$scope.isQueryDi = false;
			}
		}
		$scope.query = function () {
			$scope.tablerow = true;
			var startDate =  $scope.startDate;
			var endDate =  $scope.endDate;
			var difference  = new Date(endDate - startDate);
			var diffInDays  = difference/1000/60/60/24;
			var currdate = new Date();
			if(endDate > currdate) {
				alert("End Date can't be more than current Date");
				$scope.endDate = currdate;
			//$scope.isQueryDi = true;
			}
			else if(startDate > endDate){
				$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
			}
			else if(diffInDays>7) {
				alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
			}
			else {
				$http({
					method: 'post',
					url: '../ajax/jentryajax.php',
					data: {
					action: 'findlist',
					partyCode: $scope.partyCode,
					topartyCode:$scope.topartyCode,
					startDate:$scope.startDate,
					endDate:$scope.endDate,
					creteria:$scope.creteria
					},
				}).then(function successCallback(response) {
					$scope.jentrys = response.data;
				}, function errorCallback(response) {
					console.log(response.data);
				});
			}
		}
	});
app.controller('ajEntryCtrl', function ($scope, $http, $filter) {
	$scope.isHideOk = true;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.fn_load = function (partyType,partyCode) {
	if(partyType == 'C' || partyType == 'A') {
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params:
				{ partyType:partyType,
				partyCode:partyCode,
				action: 'infolist'
			},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});
		}
	}
	$scope.partyload = function (partyType) {
		var action = "";var fora="";
		if(partyType == "MA") {
			fora = "agent";
			type = "N";
		}
		if(partyType == "SA") {
			fora = "agent";
			type = "Y";
		}
		if(partyType == "C") {
			fora = "champion";
			type = "";
		}
		if(partyType == "P") {
			fora = "personal";
			type = "";
		}
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:fora,
			   type: type
				},
		}).then(function successCallback(response) {
			$scope.infos = response.data;
		});
	}
		$scope.checkdate = function (startDate,endDate){
			var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
			var currdate = new Date();
			var difference  = new Date(endDate - startDate);
			var diffInDays  = difference/1000/60/60/24;
			if(endDate > currdate) {
				alert("End Date can't be more than current Date");
				$scope.endDate = currdate;
			//$scope.isQueryDi = true;
			}
			else if(startDate > endDate){
				$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
			}
			else if(diffInDays>7) {
				alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
			}
			else {
				$scope.isQueryDi = false;
			}
		}
		$scope.query = function () {
			$scope.tablerow = true;
			var startDate =  $scope.startDate;
			var endDate =  $scope.endDate;
			var difference  = new Date(endDate - startDate);
			var diffInDays  = difference/1000/60/60/24;
			var currdate = new Date();
			if(endDate > currdate) {
				alert("End Date can't be more than current Date");
				$scope.endDate = currdate;
			//$scope.isQueryDi = true;
			}
			else if(startDate > endDate){
				$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
			}
			else if(diffInDays>7) {
				alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
			}
			else {
				$http({
					method: 'post',
					url: '../ajax/ajentryajax.php',
					data: {
					action: 'findlist',
					partyCode: $scope.partyCode,
					topartyCode:$scope.topartyCode,
					startDate:$scope.startDate,
					endDate:$scope.endDate,
					creteria:$scope.creteria
					},
				}).then(function successCallback(response) {
					$scope.jentrys = response.data;
				}, function errorCallback(response) {
					console.log(response.data);
				});
			}
		}
	});
app.controller('CmpFnReportCtrl', function ($scope, $http) {
	$scope.startDate = new Date();
	$scope.tablerow = true;
	$scope.endDate = new Date();
	$scope.ba = 'ra';
	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'servfeaforcode',action:'active' },
		}).then(function successCallback(response) {
			$scope.types = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
		$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'cmpforagent', "type": "N" }
	}).then(function successCallback(response) {
		$scope.agents = response.data;
		//window.location.reload();
	});
	$scope.radiochange = function () {
		$scope.tablerow = false;
	}
	 $scope.impor =function () {
     $scope.tablerow = false;
}
	$scope.reset = function () {
		$scope.tablerow = false;
		$scope.startDate = new Date();
		$scope.endDate = new Date();
		$scope.orderdetail = true;
		$scope.championdetail = false;
		$scope.agentdetail = false;
		$scope.AgentName = "ALL";
		$scope.type = "ALL";
		$scope.ba = 'ra';
	}
	$scope.query = function () {
		$scope.tablerow = true;
		var startDate =  $scope.startDate;
		var endDate =  $scope.endDate;
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		var currdate = new Date();
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
    	else {
			$http({
				method: 'post',
				url: '../ajax/cmpfnreportajax.php',
				data: {
					action: 'getreport',
					type: $scope.type,
					agentName: $scope.agentName,
					cmpName: $scope.cmpName,
					agentDetail: $scope.agentdetail,
					championDetail: $scope.championdetail,
					typeDetail: $scope.orderdetail,
					startDate: $scope.startDate,
					endDate: $scope.endDate,
					ba:$scope.ba,
				},
			}).then(function successCallback(response) {
				$scope.res = response.data;
				$scope.td = response.data[0].td;
				$scope.ad =response.data[0].ad;
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
     }

});
app.controller('CmptransReportCtrl', function ($scope, $http) {		$scope.startDate = new Date();
	$scope.tablerow = true;
	$scope.endDate = new Date();
	$scope.ba = 'ra';
	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'servfeaforcode',action:'active' },
		}).then(function successCallback(response) {
			$scope.types = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
		$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'cmpforagent', "type": "N" }
	}).then(function successCallback(response) {
		$scope.agents = response.data;
		//window.location.reload();
	});
	$scope.radiochange = function () {
		$scope.tablerow = false;
	}
	 $scope.impor =function () {
     $scope.tablerow = false;
	}
	$scope.reset = function () {
		$scope.tablerow = false;
		$scope.startDate = new Date();
		$scope.endDate = new Date();
		$scope.orderdetail = true;
		$scope.championDetail = false;
		$scope.agentdetail = false;
		$scope.AgentName = "ALL";
		$scope.type = "ALL";
		$scope.ba = 'ra';
	}
	$scope.query = function () {
		$scope.tablerow = true;
		var startDate =  $scope.startDate;
		var endDate =  $scope.endDate;
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		var currdate = new Date();
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
    	else {
			$http({
				method: 'post',
				url: '../ajax/cmptransreportajax.php',
				data: {
					action: 'getreport',
					type: $scope.type,
					agentName: $scope.agentName,
					cmpName: $scope.cmpName,
					agentDetail: $scope.agentdetail,
					championDetail: $scope.championdetail,
					typeDetail: $scope.orderdetail,
					startDate: $scope.startDate,
					endDate: $scope.endDate,
					ba:$scope.ba,
				},
			}).then(function successCallback(response) {
				$scope.res = response.data;
				$scope.td = response.data[0].td;
				$scope.ad =response.data[0].ad;
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
     }

});
app.controller('AgtfnReportCtrl', function ($scope, $http) {
	$scope.startDate = new Date();
	$scope.tablerow = true;
	$scope.endDate = new Date();
	$scope.orderdetail = true;
	$scope.ba = 'ra';

	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'servfeaforcode',action:'active' },
		}).then(function successCallback(response) {
			$scope.types = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'subforagent', "type": "N" }
	}).then(function successCallback(response) {
		$scope.subagents = response.data;
		//window.location.reload();
	});

	$scope.radiochange = function () {
		$scope.tablerow = false;
	}
 	$scope.impor =function () {
		$scope.tablerow = false;
	}
	$scope.reset = function () {
		$scope.tablerow = false;
		$scope.startDate = new Date();
		$scope.endDate = new Date();
		$scope.orderdetail = true;
		$scope.agentdetail = false;
		$scope.subagentdetail = false;
		$scope.subAgentName = "ALL";
		$scope.type = "ALL";
		$scope.ba = 'ra';
	}
	$scope.query = function () {
		$scope.tablerow = true;
		var startDate =  $scope.startDate;
		var endDate =  $scope.endDate;
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		var currdate = new Date();
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
    	else {
			$http({
				method: 'post',
				url: '../ajax/agtfnreportajax.php',
				data: {
					action: 'getreport',
					type: $scope.type,
					agentName: $scope.agentName,
					subAgentName: $scope.subAgentName,
					agentDetail: $scope.agentdetail,
					subagentDetail: $scope.subagentdetail,
					typeDetail: $scope.orderdetail,
					startDate: $scope.startDate,
					endDate: $scope.endDate,
					ba:$scope.ba,
					creteria: $scope.creteria
				},
			}).then(function successCallback(response) {
				$scope.res = response.data;
				$scope.td = response.data[0].td;
				$scope.ad =response.data[0].ad;
				$scope.subagent =response.data[0].subagent;
				$scope.agent =response.data[0].agent;
				$scope.parent =response.data[0].parent;
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
     }
});
app.controller('Agttranreportctrl', function ($scope, $http, $filter) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'servfeaforcode',action:'active' },
		}).then(function successCallback(response) {
			$scope.types = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
		$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'subforagent' }
	}).then(function successCallback(response) {
		$scope.subagents = response.data;
		//window.location.reload();
	});
	  $scope.impor =function () {
      $scope.tablerow = false;
}
$scope.viewcomm = function (no) {
	$http({
			method: 'post',
			url: '../ajax/trreportajax.php',
			data: {
                action: 'viewcomm',
                orderNo: no
            },
		}).then(function successCallback(response) {
			$scope.no =no;
			$scope.rescomms = response.data;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.view = function (no) {
		$http({
			method: 'post',
			url: '../ajax/trreportajax.php',
			data: {
                action: 'view',
                orderNo: no
            },
		}).then(function successCallback(response) {
			$scope.no = response.data[0].no;
			$scope.code = response.data[0].code;
			$scope.transLogId = response.data[0].transLogId;
			$scope.toamount = response.data[0].toamount;
			$scope.rmount = response.data[0].rmount;
			$scope.user = response.data[0].user;
			$scope.amscharge = response.data[0].amscharge;
			$scope.parcharge = response.data[0].parcharge;
			$scope.ocharge = response.data[0].ocharge;
			$scope.name = response.data[0].name;
			$scope.mobile = response.data[0].mobile;
			$scope.auth = response.data[0].auth;
			$scope.refNo = response.data[0].refNo;
			$scope.comment = response.data[0].comment;
			$scope.dtime = response.data[0].dtime;
			$scope.pstatus = response.data[0].pstatus;
			$scope.ptime = response.data[0].ptime;
			$scope.sconfid = response.data[0].sconfid;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
$scope.reset = function () {
		$scope.tablerow = false;
		$scope.startDate = new Date();
		$scope.endDate = new Date();
		$scope.orderdetail = true;
		$scope.agentdetail = false;
		$scope.subagentdetail = false;
		$scope.subAgentName = "ALL";
		$scope.type = "ALL";
		$scope.ba = 'ra';
	}
	$scope.query = function () {
		$scope.tablerow = true;
		var startDate =  $scope.startDate;
		var endDate =  $scope.endDate;
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		var currdate = new Date();
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
    	else {
			$http({
				method: 'post',
				url: '../ajax/agttransreportajax.php',
				data: {
					action: 'getreport',
					type: $scope.type,
					startDate: $scope.startDate,
					endDate: $scope.endDate,
					ba:$scope.ba,
					creteria:$scope.creteria,
					orderNo:$scope.orderNo
				},
			}).then(function successCallback(response) {
				$scope.res = response.data;
				$scope.td = response.data[0].td;
				$scope.ad =response.data[0].ad;
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
     }

});
app.controller('CmpStatReportCtrl', function ($scope, $http, $filter) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.orderdetail = true;
	$scope.tablerow = true;
	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'servfeaforcode',action:'active' },
		}).then(function successCallback(response) {
			$scope.types = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
		$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'cmpforagent', "type": "N" }
	}).then(function successCallback(response) {
		$scope.agents = response.data;
		//window.location.reload();
	});
	$scope.radiochange = function () {
		$scope.tablerow = false;
	}
	 $scope.impor =function () {
     $scope.tablerow = false;
}
	$scope.reset = function () {
		$scope.tablerow = false;
		$scope.startDate = new Date();
		$scope.endDate = new Date();
		$scope.orderdetail = true;
		$scope.agentdetail = false;
		$scope.agentName = "ALL";
		$scope.type = "ALL";

	}
	$scope.query = function () {
		$scope.tablerow = true;
		var startDate =  $scope.startDate;
		var endDate =  $scope.endDate;
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		var currdate = new Date();
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
			$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
			alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
		}
		else {
			$http({
				method: 'post',
				url: '../ajax/cmpstatereportajax.php',
				data: {
					action: 'getreport',
					type: $scope.type,
					agentName: $scope.agentName,
					agentDetail: $scope.agentDetail,
					typeDetail: $scope.orderdetail,
					startDate: $scope.startDate,
					endDate: $scope.endDate,
					creteria: $scope.creteria
				},
			}).then(function successCallback(response) {
				$scope.res = response.data;
				$scope.td = response.data[0].td;
				$scope.ad =response.data[0].ad;
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
	}

});

 app.controller('tier1Ctrl', ['$scope','$http', function($scope,$http ){
	$scope.isHideOk = true;
	$scope.isHideReset = false;
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'country' }
	}).then(function successCallback(response) {
		$scope.countrys = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'partners' }
		}).then(function successCallback(response) {
				$scope.partners = response.data;
		//window.location.reload();
	});
$scope.countrychange = function (id) {
	$http({
		method: 'post',
		url: '../ajax/load.php',
		params: { for: 'statelist', "id": id, "action": "active" },
	}).then(function successCallback(response) {
		$scope.states = response.data;
	}, function errorCallback(response) {
		// console.log(response);
	});
}
$scope.create = function () {
 var fd = new FormData();
   angular.forEach($scope.uploadfiles,function(file){
     fd.append('file[]',file);
   });
   fd.append("action","create");
   fd.append("country",$scope.country);
   fd.append("state",$scope.state);
   fd.append("mobileno",$scope.mobileno);
   fd.append("bvn",$scope.bvn);
   fd.append("email",$scope.email);
   fd.append("firstName",$scope.firstName);
   fd.append("lastName",$scope.lastName);
   fd.append("dob",$scope.dob);
   fd.append("refmobileno",$scope.refmobileno);
   fd.append("attachment",$scope.attachment);
   fd.append("partner",$scope.partner);
	$http({
		method: 'post',
		url: '../ajax/tieracajax.php',
		headers: {'Content-Type': undefined},
		ContentType: 'application/json',
		data: fd,
	}).then(function successCallback(response) {
		$scope.isHide = true;
		$scope.isHideOk = false;
		$scope.isHideReset = true;
		$scope.isLoader = false;
		$("#TieracCreateBody").html("<h3>" + response.data.errorResponseDescription + "</h3>");
	}, function errorCallback(response) {
		console.log(response.data);
	});
};

}]);
app.controller('statReportCtrl', function ($scope, $http, $filter) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.orderdetail = true;
	$scope.tablerow = true;
	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'servfeaforcode',action:'active' },
		}).then(function successCallback(response) {
			$scope.types = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'agent', "type": "N" }
	}).then(function successCallback(response) {
		$scope.agents = response.data;
		//window.location.reload();
	});
	$scope.reset = function () {
		$scope.tablerow = false;
		$scope.startDate = new Date();
		$scope.endDate = new Date();
		$scope.orderdetail = true;
		$scope.agentdetail = false;
		$scope.agentName = "ALL";
		$scope.type = "ALL";

	}
	 $scope.impor =function () {
     $scope.tablerow = false;
}
	$scope.query = function () {
		$scope.tablerow = true;
		var startDate =  $scope.startDate;
		var endDate =  $scope.endDate;
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		var currdate = new Date();
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
			$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
			alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
		}
		else {
			$http({
				method: 'post',
				url: '../ajax/statereportajax.php',
				data: {
					action: 'getreport',
					type: $scope.type,
					agentName: $scope.agentName,
					agentDetail: $scope.agentdetail,
					typeDetail: $scope.orderdetail,
					startDate: $scope.startDate,
					endDate: $scope.endDate
				},
			}).then(function successCallback(response) {
				$scope.res = response.data;
				$scope.td = response.data[0].td;
				$scope.ad =response.data[0].ad;
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
	}
	$scope.checkdate = function (startDate,endDate){
		var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
		var currdate = new Date();
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
			$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
			alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
		}
		else {
			$scope.isQueryDi = false;
		}
	}
});
app.controller('fnReportCtrl', function ($scope, $http) {
	$scope.startDate = new Date();
	$scope.tablerow = true;
	$scope.endDate = new Date();
	$scope.ba = 'ra';
	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'servfeaforcode',action:'active' },
		}).then(function successCallback(response) {
			$scope.types = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'agent', "type": "N" }
	}).then(function successCallback(response) {
		$scope.agents = response.data;
		//window.location.reload();
	});
	$scope.radiochange = function () {
		$scope.tablerow = false;
	}
	$scope.impor =function () {
     $scope.tablerow = false;
}
	$scope.reset = function () {
		$scope.tablerow = false;
		$scope.startDate = new Date();
		$scope.endDate = new Date();
		$scope.orderdetail = true;
		$scope.agentdetail = false;
		$scope.agentName = "ALL";
		$scope.type = "ALL";
		$scope.ba = 'ra';
	}
	$scope.query = function () {
		$scope.tablerow = true;
		var startDate =  $scope.startDate;
		var endDate =  $scope.endDate;
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		var currdate = new Date();
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
			$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
			alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
		}
		else {
			$http({
				method: 'post',
				url: '../ajax/fnreportajax.php',
				data: {
					action: 'getreport',
					type: $scope.type,
					agentName: $scope.agentName,
					agentDetail: $scope.agentdetail,
					typeDetail: $scope.orderdetail,
					startDate: $scope.startDate,
					endDate: $scope.endDate,
					ba:$scope.ba
				},
			}).then(function successCallback(response) {
				$scope.res = response.data;
				$scope.td = response.data[0].td;
				$scope.ad =response.data[0].ad;
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
	}
	$scope.checkdate = function (startDate,endDate){
		var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
		var currdate = new Date();
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
			$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
			alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
		}
		else {
			$scope.isQueryDi = false;
		}
	}
});
app.controller('jcomentryCtrl', function ($scope, $http, $filter) {
	$scope.isHideOk = true;
	$scope.startDate = new Date();
	$scope.endDate = new Date();

	$scope.partyload = function (partyType) {
		var action = "";var fora="";
		if(partyType == "MA") {
			fora = "agent";
			type = "N";
		}
		if(partyType == "SA") {
			fora = "agent";
			type = "Y";
		}
		if(partyType == "C") {
			fora = "champion";
			type = "";
		}
		if(partyType == "P") {
			fora = "personal";
			type = "";
		}
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:fora,
			   type: type
				},
		}).then(function successCallback(response) {
			$scope.infos = response.data;
		});
	}
		$scope.checkdate = function (startDate,endDate){
			var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
			var currdate = new Date();
			var difference  = new Date(endDate - startDate);
			var diffInDays  = difference/1000/60/60/24;
			if(endDate > currdate) {
				alert("End Date can't be more than current Date");
				$scope.endDate = currdate;
			//$scope.isQueryDi = true;
			}
			else if(startDate > endDate){
				$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
			}
			else if(diffInDays>7) {
				alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
			}
			else {
				$scope.isQueryDi = false;
			}
		}
		$scope.query = function () {
			$scope.tablerow = true;
			var startDate =  $scope.startDate;
			var endDate =  $scope.endDate;
			var difference  = new Date(endDate - startDate);
			var diffInDays  = difference/1000/60/60/24;
			var currdate = new Date();
			if(endDate > currdate) {
				alert("End Date can't be more than current Date");
				$scope.endDate = currdate;
			//$scope.isQueryDi = true;
			}
			else if(startDate > endDate){
				$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
			}
			else if(diffInDays>7) {
				alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
			}
			else {
				$http({
					method: 'post',
					url: '../ajax/jcomentryajax.php',
					data: {
						action: 'findlist',
						partyCode: $scope.partyCode,
						startDate:$scope.startDate,
						endDate:$scope.endDate,
					},
				}).then(function successCallback(response) {
					$scope.jentrys = response.data;
				}, function errorCallback(response) {
					console.log(response.data);
				});
			}
		}
	});

app.controller('pOutCtrl', function ($scope, $http, $filter) {
	$scope.isHideOk = true;
	$scope.startDate = new Date();
	$scope.endDate = new Date();

	$scope.partyload = function (partyType) {
		var action = "";var fora="";
		if(partyType == "MA") {
			fora = "agent";
			type = "N";
		}
		if(partyType == "SA") {
			fora = "agent";
			type = "Y";
		}
		if(partyType == "C") {
			fora = "champion";
			type = "";
		}
		if(partyType == "P") {
			fora = "personal";
			type = "";
		}
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:fora,
			   type: type
				},
		}).then(function successCallback(response) {
			$scope.infos = response.data;
		});
	}
		$scope.checkdate = function (startDate,endDate){
			var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
			var currdate = new Date();
			var difference  = new Date(endDate - startDate);
			var diffInDays  = difference/1000/60/60/24;
			if(endDate > currdate) {
				alert("End Date can't be more than current Date");
				$scope.endDate = currdate;
			//$scope.isQueryDi = true;
			}
			else if(startDate > endDate){
				$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
			}
			else if(diffInDays>7) {
				alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
			}
			else {
				$scope.isQueryDi = false;
			}
		}
		$scope.query = function () {
			$scope.tablerow = true;
			var startDate =  $scope.startDate;
			var endDate =  $scope.endDate;
			var difference  = new Date(endDate - startDate);
			var diffInDays  = difference/1000/60/60/24;
			var currdate = new Date();
			if(endDate > currdate) {
				alert("End Date can't be more than current Date");
				$scope.endDate = currdate;
			//$scope.isQueryDi = true;
			}
			else if(startDate > endDate){
				$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
			}
			else if(diffInDays>7) {
				alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
			}
			else {
				$http({
					method: 'post',
					url: '../ajax/payoutajax.php',
					data: {
						action: 'paylist',
						partyCode: $scope.partyCode,
						startDate:$scope.startDate,
						endDate:$scope.endDate,
					},
				}).then(function successCallback(response) {
					$scope.payouts = response.data;
				}, function errorCallback(response) {
					console.log(response.data);
				});
			}
		}
	$scope.detail = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/payoutajax.php',
			data: { id: id, action: 'detail' },
		}).then(function successCallback(response) {
			$scope.id = response.data[0].id;
			$scope.partype = response.data[0].partype;
			$scope.parcode = response.data[0].parcode;
			$scope.name = response.data[0].name;
			$scope.type = response.data[0].paytype;
			$scope.cuser = response.data[0].cuser;
			$scope.uuser = response.data[0].uuser;
			$scope.status = response.data[0].status;
			$scope.payamount = response.data[0].payamount;
			$scope.proamount = response.data[0].proamount;
			$scope.localgovt = response.data[0].localgovt;
			$scope.totamount = response.data[0].totamount;
			$scope.date = response.data[0].date;
			$scope.utime = response.data[0].utime;
			$scope.ctime = response.data[0].ctime;
			$scope.uuser = response.data[0].uuser;
			$scope.bank = response.data[0].bank;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});
app.controller('commviewCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.partyload = function (partyType) {
		var action = "";var fora="";
		if(partyType == "MA") {
			fora = "agent";
			type = "N";
		}
		if(partyType == "SA") {
			fora = "agent";
			type = "Y";
		}
		if(partyType == "C") {
			fora = "champion";
			type = "";
		}
		if(partyType == "P") {
			fora = "personal";
			type = "";
		}
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:fora,
				    type: type
				},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});

	}
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/commviewajax.php',
			data: {
				action: 'findlist',
				partyCode: $scope.partyCode,
				partyType: $scope.partyType,
			},
		}).then(function successCallback(response) {
			$scope.commviews = response.data;


		}, function errorCallback(response) {
			console.log(response.data);
		});
	}
	$scope.edit = function (index, code) {
		$http({
			method: 'post',
			url: '../ajax/commviewajax.php',
			data: { code: code,type: $scope.partyType, action: 'edit', },
		}).then(function successCallback(response) {

			$scope.climit = response.data[0].climit;
			$scope.dvamt = response.data[0].dvamt;
			$scope.avlbalance = response.data[0].avlbalance;
			$scope.minbalance = response.data[0].minbalance;
			$scope.precbal = response.data[0].precbal;
			$scope.active = response.data[0].active;
			$scope.blkdate = response.data[0].blkdate;
			$scope.brenid = response.data[0].brenid;
			$scope.blkstatus = response.data[0].blkstatus;
			$scope.code = response.data[0].code;
			$scope.ctime = response.data[0].ctime;
			$scope.cuser = response.data[0].cuser;
			$scope.advamt = response.data[0].advamt;
			$scope.dlimit = response.data[0].dlimit;
			$scope.ltamt = response.data[0].ltamt;
			$scope.ltdate = response.data[0].ltdate;
			$scope.ltno = response.data[0].ltno;
			$scope.lname = response.data[0].lname;
			$scope.pcode = response.data[0].pcode;
			$scope.uuser = response.data[0].uuser;
			$scope.utime = response.data[0].utime;
			$scope.ucbal = response.data[0].ucbal;
			$scope.curbal = response.data[0].curbal;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});

app.controller('tier1AcstsCtrl', function ($scope, $http) {
	$scope.isLoader = true;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.isMainLoader = true;
	$scope.isHideOk = true;
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/tieracstaajax.php',
			data: {
				creteria:$scope.creteria,
				crestatus: $scope.crestatus,
				startDate: $scope.startDate,
				endDate: $scope.endDate,
				mobileNumber: $scope.mobileNumber,
				action: 'query'
			},
		}).then(function successCallback(response) {
			$scope.payouts = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}

	$scope.view = function (index, mobile) {
		$("#tableres").html("");
		$http({
			method: 'post',
			url: '../ajax/tieracstaajax.php',
			data: {
				mobileNumber: mobile,
				action: 'post'
			},
		}).then(function successCallback(response) {

			var respotable = "<table class='table table-bordered'><thead><tr><th>Name</th><th>Reference</th><th>Account No</th></tr></thead><tbody>";
			for(var i=0;i<response.data.mobileAccountData.length;i++) {
				respotable += "<tr><td>"+response.data.mobileAccountData[i].name+"</td><td>"+response.data.mobileAccountData[i].reference+"</td><td>"+ response.data.mobileAccountData[i].accountNumber+"</td></tr>";
			}
			respotable += "</tbody></table>";
			$("#tableres").html(respotable);
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});

app.controller('gOtpCtrl', function ($scope, $http) {
	$scope.isResDiv = true;
	$scope.reset = function () {
		$scope.mobileno = "";
		$scope.accountno= "";
		$scope.amount = "";
		$scope.isResDiv = true;
	}
	$scope.gotp = function () {
		$http({
			method: 'post',
			url: '../ajax/gotpajax.php',
			data: {
				action: 'gotp',
				mobile: $scope.mobileno,
				accountno: $scope.accountno,
				amount: $scope.amount
			},
		}).then(function successCallback(response) {
			$scope.isResDiv = false;
			$scope.responseDescription = response.data.responseDescription;
			$scope.responseCode = response.data.responseCode;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});
app.controller('trReportCtrl', function ($scope, $http, $filter) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.isOrderNoDi = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.tablerow = true;
	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'servfeaforcode',action:'active' },
		}).then(function successCallback(response) {
			$scope.types = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	$scope.checkdate = function (startDate,endDate){
		var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
		var currdate = new Date();
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
			$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
			alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
		}
		else {
			$scope.isQueryDi = false;
		}
	}
	 $scope.impor =function () {
     $scope.tablerow = false;
}
	$scope.viewcomm = function (no) {
	$http({
			method: 'post',
			url: '../ajax/trreportajax.php',
			data: {
                action: 'viewcomm',
                orderNo: no
            },
		}).then(function successCallback(response) {
			$scope.no =no;
			$scope.rescomms = response.data;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.view = function (no) {
		$http({
			method: 'post',
			url: '../ajax/trreportajax.php',
			data: {
                action: 'view',
                orderNo: no
            },
		}).then(function successCallback(response) {
			$scope.no = response.data[0].no;
			$scope.code = response.data[0].code;
			$scope.transLogId = response.data[0].transLogId;
			$scope.toamount = response.data[0].toamount;
			$scope.rmount = response.data[0].rmount;
			$scope.user = response.data[0].user;
			$scope.amscharge = response.data[0].amscharge;
			$scope.parcharge = response.data[0].parcharge;
			$scope.ocharge = response.data[0].ocharge;
			$scope.name = response.data[0].name;
			$scope.mobile = response.data[0].mobile;
			$scope.auth = response.data[0].auth;
			$scope.refNo = response.data[0].refNo;
			$scope.comment = response.data[0].comment;
			$scope.dtime = response.data[0].dtime;
			$scope.pstatus = response.data[0].pstatus;
			$scope.ptime = response.data[0].ptime;
			$scope.sconfid = response.data[0].sconfid;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.query = function () {
		$scope.tablerow = true;
		var startDate =  $scope.startDate;
		var endDate =  $scope.endDate;
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		var currdate = new Date();
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
			$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
			alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
		}
		else {
			$scope.dateerr ="";
			$http({
				method: 'post',
				url: '../ajax/trreportajax.php',
				data: {
					action: 'getreport',
					type: $scope.type,
					orderNo: $scope.orderNo,
					startDate: $scope.startDate,
					endDate: $scope.endDate,
					creteria: $scope.creteria
				},
			}).then(function successCallback(response) {
				$scope.res = response.data;
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
	}
	$scope.reset = function () {
		$scope.tablerow = false;
		$scope.startDate = new Date();
		$scope.endDate = new Date();
		$scope.orderdetail = true;
		$scope.type = "ALL";
		$scope.orderno = "";
		$scope.isOrderNoDi = true;

	}
	$scope.clickra = function (clickra) {
		$scope.orderno = "";
		$scope.type = "";
		$scope.startDate = new Date();
		$scope.endDate = new Date();
		if(clickra == "BT") {
			$scope.isOrderNoDi = true;
			$scope.isStartDateDi = false;
			$scope.isEndDateDi = false;
			$scope.orderno = "";
			$scope.isOrderTypeDi = false;
			$scope.startDate = new Date();
			$scope.type = "ALL";
			$scope.endDate = new Date();
		}
		if(clickra == "BO") {
			$scope.isOrderNoDi = false;
			$scope.isStartDateDi = true;
			$scope.isEndDateDi = true;
			$scope.isOrderTypeDi = true
			$scope.startDate = "";
			$scope.endDate = "";
		}

	}
});
app.directive('ngConfirmClick', [
	function () {
		return {
			link: function (scope, element, attr) {
				var msg = attr.ngConfirmClick || "Are you sure?";
				var clickAction = attr.confirmedClick;
				element.bind('click', function (event) {
					if (window.confirm(msg)) {
						scope.$eval(clickAction)
					}
				});
			}
		};
	}])

app.directive('numbersOnly', function () {
	return {
		require: 'ngModel',
		link: function (scope, element, attr, ngModelCtrl) {
			function fromUser(text) {
				if (text) {
					var transformedInput = text.replace(/[^0-9]/g, '');

					if (transformedInput !== text) {
						ngModelCtrl.$setViewValue(transformedInput);
						ngModelCtrl.$render();
					}
					return transformedInput;
				}
				return undefined;
			}
			ngModelCtrl.$parsers.push(fromUser);
		}
	};
});

app.directive('loading', ['$http', function ($http) {
	return {
		restrict: 'A',
		link: function (scope, elm, attrs) {
			scope.isLoading = function () {
				return $http.pendingRequests.length > 0;
			};

			scope.$watch(scope.isLoading, function (v) {
				if (v) {
					elm.show();
				} else {
					elm.hide();
				}
			});
		}
	};

}]);

app.directive('loading1', ['$http', function ($http) {
	return {
		restrict: 'A',
		link: function (scope, elm, attrs) {
			scope.isLoading = function () {
				return $http.pendingRequests.length > 0;
			};
			scope.$watch(scope.isLoading, function (v) {
				if (v) {
					elm.show();
				} else {
					elm.hide();
				}
			});
		}
	};

}]);
app.directive('restrictField', function () {
    return {
        restrict: 'AE',
        scope: {
            restrictField: '='
        },
        link: function (scope) {
          // this will match spaces, tabs, line feeds etc
          // you can change this regex as you want
          var regex = /\s/g;

          scope.$watch('restrictField', function (newValue, oldValue) {
              if (newValue != oldValue && regex.test(newValue)) {
                scope.restrictField = newValue.replace(regex, '');
              }
          });
        }
    };
});
app.directive('splCharNot', function() {
     function link(scope, elem, attrs, ngModel) {
          ngModel.$parsers.push(function(viewValue) {
            var reg = /^[^`~!@#$%\^&*()_+={}|[\]\\:';"<>?,./]*$/;
            // if view values matches regexp, update model value
            if (viewValue.match(reg)) {
              return viewValue;
            }
            // keep the model value as it is
            var transformedValue = ngModel.$modelValue;
            ngModel.$setViewValue(transformedValue);
            ngModel.$render();
            return transformedValue;
          });
      }

      return {
          restrict: 'A',
          require: 'ngModel',
          link: link
      };
  });
app.directive('ngFile', ['$parse', function ($parse) {
  return {
   restrict: 'A',
   link: function(scope, element, attrs) {
     element.bind('change', function(){

     $parse(attrs.ngFile).assign(scope,element[0].files)
     scope.$apply();
   });
  }
 };
}]);
