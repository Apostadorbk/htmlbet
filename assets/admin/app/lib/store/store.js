angular.module("storeProvider", []);

angular.module("storeProvider").provider("store", function () {

	var Store = function () {
			
		this.stack = [];
		this.indexFounded = -1;
		this.dataStored = {
			data: '',
			message: '',
			status: false
		};

		this.push = function (data, message = '', status = false) { // OK
			this.dataStored.data = data;
			this.dataStored.message = message;
			this.dataStored.status = status;

			this.stack.push(angular.copy(this.dataStored));
			return this.stack;
		};

		this.pop = function () { // OK
			
			if ( this.stack.length > 0 )
				return this.stack.pop();

			return false;

		};

		this.hasData = function (data) { // OK
			var _index = -1;
			if ( 
				this.stack.some(function (element, index, array) {
					_index = index;
					return element.data == data;
				})
			) {
				this.indexFounded = _index;
				return true;
			} else {
				this.indexFounded = -1;
				return false
			}
		};

		this.deleteData = function () { // OK

			if ( this.indexFounded < 0 ) {
				return false;
			} else {
				this.stack.splice(this.indexFounded, 1);
				return this.stack;
			}

		};

		this.getData = function () { // OK
			if ( this.indexFounded < 0 ) {
				return false;
			} else {
				return this.stack[this.indexFounded];
			}
		};
		
		this.updateMessage = function (data, message, status) { // OK
			if ( this.indexFounded < 0 ) {
				return false;
			} else {
				if ( this.hasData(data) ) {
					this.stack[this.indexFounded].message = message;
					this.stack[this.indexFounded].status = status;
					return this.stack[this.indexFounded];
				} else {
					return false;
				}
			}
		};

		this.getIndex = function () { // OK
			return this.indexFounded;
		};

		this.get = function () { // OK
			return this.stack;
		};
	}


	// O serviço que será injetado
	this.$get = function () {

		return {

			new: function () {
				return new Store();
			}

		};
	};


});