var Vue = require('vue');
// var VueResource = require('vue-resource');


// var VueFilter = require('vue-filter');
// Vue.use(VueResource, VueFilter);
Vue.use(require('vue-filter'));
// Vue.use(require('vue-resource'));

Vue.component('subembayment', {
		template: '#subembayment-template',
		props: ['id', 'title', 'percent', 'NLoad_Orig', 'NLoad_Target', 'my-effective'],
		computed: {
			NLoad_Current: function()
				{
					return this.NLoad_Orig * ((100 - this.myEffective)/100)
				},
			percent: function()
			{
				return (this.NLoad_Target/this.NLoad_Current * 100)
			}
		}
	});


new Vue({
				el: 'body',
				data:
				{
					unatt: parseFloat(nitrogen.Total_UnAtt),
					att: parseFloat(nitrogen.Total_Att),
					effective: 0,
					total_unatt: parseFloat(nitrogen.Total_UnAtt),
					total_att: parseFloat(nitrogen.Total_Att),
					fert_unatt: parseFloat(nitrogen.Total_UnAtt_Fert),
					fert_att: parseFloat(nitrogen.Total_Att_Fert),
					fert_percent: 0,
					storm_unatt: parseFloat(nitrogen.Total_UnAtt_Storm),
					storm_att: parseFloat(nitrogen.Total_Att_Storm),
					storm_percent: 0,
					atmosphere_unatt: parseFloat(nitrogen.Total_UnAtt_Atmosphere),
					atmosphere_att: parseFloat(nitrogen.Total_Att_Atmosphere)
				},
				// components: {subembayment},
				
				computed:
				{
					treated: function()
					{
						return this.att * ((100 - this.effective)/100);
						
					},
					fert_unatt_treated: function()
					{
						return (this.fert_unatt * ((100 - this.fert_percent)/100));
					},
					fert_treated: function()
					{
						return (this.fert_att * ((100 - this.fert_percent)/100));
						
					},
					fert_difference: function()
					{
						return ( this.fert_att - this.fert_treated);
					},		
					storm_unatt_treated: function()
					{
						return (this.storm_unatt * ((100 - this.storm_percent)/100));
					},
					storm_treated: function()
					{
						return (this.storm_att * ((100 - this.storm_percent)/100));
						
					},
					storm_difference: function()
					{
						return ( this.storm_att - this.storm_treated);
					},	

					total_treated: function()
					{
						return (this.total_att - this.fert_difference - this.storm_difference);
					},
					difference: function()
					{
						return ((this.total_att - this.total_treated));
					},
					groundwater_unatt: function()
					{
						return (this.fert_unatt_treated + this.storm_unatt_treated + this.atmosphere_unatt);
					},
					groundwater_att: function()
					{
						return (this.fert_treated + this.storm_treated + this.atmosphere_att);
					}
					
				}
		});