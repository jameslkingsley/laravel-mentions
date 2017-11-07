/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Mentions = function () {
    function Mentions(options) {
        _classCallCheck(this, Mentions);

        this.options = options;
        this.collections = [];

        this.input = this.findNode(this.options.input, '.has-mentions');
        this.output = this.findNode(this.options.output, '#mentions');

        this.collect().attach().listen();
    }

    _createClass(Mentions, [{
        key: 'findNode',
        value: function findNode(selector, defaultSelector) {
            return document.querySelector(selector || defaultSelector);
        }
    }, {
        key: 'template',
        value: function template(pool) {
            return function (item) {
                return '<span class="mention-node" data-object="' + pool.pool + ':' + item.original[pool.reference] + '">' + (pool.trigger || '@') + item.original[pool.display] + '</span>';
            };
        }
    }, {
        key: 'values',
        value: function values(pool) {
            return function (text, callback) {
                if (text.length <= 1) return;

                var xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        callback(JSON.parse(this.responseText));
                    }
                };

                xhttp.open('get', '/api/mentions/?p=' + pool.pool + '&q=' + text, true);
                xhttp.send();
            };
        }
    }, {
        key: 'collect',
        value: function collect() {
            for (var i = 0; i < this.options.pools.length; i++) {
                var pool = this.options.pools[i];
                this.collections.push({
                    trigger: pool.trigger || '@',
                    lookup: pool.display,
                    allowSpaces: pool.allowSpaces || true,
                    selectTemplate: this.template(pool),
                    values: this.values(pool)
                });
            }

            return this;
        }
    }, {
        key: 'attach',
        value: function attach() {
            this.tribute = new Tribute({
                collection: this.collections
            });

            this.tribute.attach(this.input);

            return this;
        }
    }, {
        key: 'listen',
        value: function listen() {
            var instance = this;

            this.input.addEventListener('keyup', function (event) {
                var input = event.target;
                var mentions = instance.output;
                var nodeList = [];

                var nodes = input.getElementsByClassName('mention-node');

                for (var i = 0; i < nodes.length; i++) {
                    nodeList.push(nodes[i].getAttribute('data-object'));
                }

                mentions.value = nodeList.join();

                if (input.hasAttribute('for') && !(instance.options.ignoreFor || false)) {
                    document.querySelector(input.getAttribute('for')).value = input.innerHTML;
                }
            });
        }
    }]);

    return Mentions;
}();

/* harmony default export */ __webpack_exports__["default"] = (Mentions);

/***/ }),
/* 1 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(0);
module.exports = __webpack_require__(1);


/***/ })
/******/ ]);