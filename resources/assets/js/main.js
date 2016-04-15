var Vue = require('vue');
var VueResource = require('vue-resource');
Vue.use(VueResource);

var VueFilter = require('vue-filter');
Vue.use(VueFilter);

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
					unatt: nitrogen.Total_UnAtt,
					att: nitrogen.Total_Att,
					effective: 0,
					total_unatt: nitrogen.Total_UnAtt,
					total_att: nitrogen.Total_Att,
					fert_unatt: nitrogen.Total_UnAtt_Fert,
					fert_att: nitrogen.Total_Att_Fert,
					fert_percent: 0,
					storm_unatt: nitrogen.Total_UnAtt_Storm,
					storm_att: nitrogen.Total_Att_Storm,
					storm_percent: 0,
					atmosphere_unatt: nitrogen.Total_UnAtt_Atmosphere,
					atmosphere_att: nitrogen.Total_Att_Atmosphere
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
						return Math.round(this.fert_unatt * ((100 - this.fert_percent)/100));
					},
					fert_treated: function()
					{
						return Math.round(this.fert_att * ((100 - this.fert_percent)/100));
						
					},
					fert_difference: function()
					{
						return Math.round( this.fert_att - this.fert_treated);
					},		
					storm_unatt_treated: function()
					{
						return Math.round(this.storm_unatt * ((100 - this.storm_percent)/100));
					},
					storm_treated: function()
					{
						return Math.round(this.storm_att * ((100 - this.storm_percent)/100));
						
					},
					storm_difference: function()
					{
						return Math.round( this.storm_att - this.storm_treated);
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