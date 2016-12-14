var Vue = require('vue');
Vue.use(require('vue-filter'));
Vue.use(require('vue-resource'));

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
				http: {
				  // root: '/root',
				  headers: {
					// 'X-CSRF-TOKEN': document.querySelector('#token').getAttribute('value')
					'X-CSRF-TOKEN': 'IonQmqAAs09oCEnlKfmYSuW5OoXQgQaswjCVLRWL'
				  }
				},
				el: 'body',
				data:
				{
					// poly_nitrogen: poly_nitrogen.Septic,
					unatt: parseFloat(nitrogen_unatt.Total_UnAtt),
					att: parseFloat(nitrogen_att.Total_Att),
					treatment: 13,
					effective: 0,
					total_unatt: parseFloat(nitrogen_unatt.Total_UnAtt),
					total_att: parseFloat(nitrogen_att.Total_Att),
					fert_unatt: parseFloat(nitrogen_unatt.Total_UnAtt_Fert),
					fert_att: parseFloat(nitrogen_att.Total_Att_Fert),
					fert_percent: 0,
					storm_unatt: parseFloat(nitrogen_unatt.Total_UnAtt_Storm),
					storm_att: parseFloat(nitrogen_att.Total_Att_Storm),
					storm_percent: 0,
					septic_unatt: parseFloat(nitrogen_unatt.Total_UnAtt_Septic),
					septic_att: parseFloat(nitrogen_att.Total_Att_Septic),
					septic_rate: 0,
					atmosphere_unatt: parseFloat(nitrogen_unatt.Total_UnAtt_Atmosphere),
					atmosphere_att: parseFloat(nitrogen_att.Total_Att_Atmosphere),
					embayment_percent: 0
				},


				computed:
				{
					// treated: function()
					// {
					// 	return this.att * ((100 - this.effective)/100);
					// },
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
					storm_att_treated: function()
					{
						return (this.storm_att * ((100 - this.storm_percent)/100));
					},
					storm_difference: function()
					{
						return ( this.storm_att - this.storm_att_treated);
					},	

					groundwater_unatt: function()
					{
						return (this.fert_unatt_treated + this.storm_unatt_treated + this.atmosphere_unatt);
					},
					groundwater_att: function()
					{
						return (this.fert_treated + this.storm_treated + this.atmosphere_att);
					},
					groundwater_treated: function()
					{
						return (this.groundwater_att * ((100-this.ground_percent)/100));
					},
					embayment_treated: function()
					{
						return (this.total_treated * ((100-this.embayment_percent)/100));
					},
					embayment_difference: function()
					{
						return (this.total_treated - this.embayment_treated);
					}
					
				}
		});