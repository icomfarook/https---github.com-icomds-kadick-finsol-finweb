app.directive('splCharExDot', function() {
     function link(scope, elem, attrs, ngModel) {
          ngModel.$parsers.push(function(viewValue) {
            var reg = /^[^`~!@#$%\^&*()_+={}|[\]\\:';"<>?,/]*$/;
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
	}]);

app.directive('validFile',function(){
    return {
        require:'ngModel',
        link:function(scope,el,attrs,ctrl){
            ctrl.$setValidity('validFile', el.val() != '');
            //change event is fired when file is selected
            el.bind('change',function(){
                ctrl.$setValidity('validFile', el.val() != '');
                scope.$apply(function(){
                    ctrl.$setViewValue(el.val());
                    ctrl.$render();
                });
            });
        }
    }
})
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
					//$(".row").css("opacity", 0.1);
					$("form").css("opacity", 0.1);
					$("input,button").hide();
					$("input,button").css("cursor", "none");
					//$(".box-content").css({"display": "block"});
					elm.show();
				} else {
					//$(".row").css("opacity", 1.0);
					$("form").css("opacity", 1.0);
					$("input,button").show();
					$("input").css("cursor", "auto");
					$("button").css("cursor", "pointer");
					//$(".box-content").css({"display": "inline-block"});
					elm.hide();
				}
			});
		}
	};

}]);

app.directive('disableRightClick', function() {
   return {
       restrict: 'A',
       link: function(scope, element, attr) {
           element.bind('contextmenu', function(e) {
               e.preventDefault();
           })
       }
   }
});

app.directive('loading1', ['$http', function ($http) {
	return {
		restrict: 'A',
		link: function (scope, elm, attrs) {
			scope.isLoading = function () {
				return $http.pendingRequests.length > 0;
			};
			scope.$watch(scope.isLoading, function (v) {
				if (v) {
					//$(".row").css("opacity", 0.1);
					$("form").css("opacity", 0.1);
					$("input,button").css("cursor", "none");
					$("input,button").hide();
					//$(".box-content").css({"display": "block"});
					elm.show();
				} else {
					//$(".row").css("opacity", 1.0);
					$("form").css("opacity", 1.0);
					$("input,button").show();
					$("input").css("cursor", "auto");
					$("button").css("cursor", "pointer");
					//$(".box-content").css({"display": "inline-block"});
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
