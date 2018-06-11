var student = {
	id : '',
	userName : '',
	firstName : '',
	lastName : '',
	alerts : {
		dismissed : function() {return localStorage.getItem('alertsDismissed')}
	}
}

export {student}
