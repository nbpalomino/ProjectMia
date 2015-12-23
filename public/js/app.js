$(document).ready(function(){
	var bugsy = new Vue({
		el: '#search',

		data: {
			bug:''
		},

		methods:{
			doSearch: function(){
				console.log('doSearch');
				this.bug = this.bug.toUpperCase();
			},
		}
	})
});