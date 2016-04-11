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
					// unatt: <?php echo round($row['Total_UnAtt'], 2);?>,
					// att: <?php echo round($row['Total_Att'], 2);?>,
					// effective: 0,
					// total_unatt: <?php echo ($row['Total_UnAtt']);?>,
					// total_att: <?php echo ($row['Total_Att']);?>,
					// fert_unatt: <?php echo ($row['Total_UnAtt_Fert']);?>,
					// fert_att: <?php echo ($row['Total_Att_Fert']);?>,
					// fert_percent: 0

					unatt: 0,
					att: 0,
					effective: 0,
					total_unatt: 100,
					total_att: 100,
					fert_unatt: 100,
					fert_att: 100,
					fert_percent: 0
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
						return Math.round(this.fert_unatt * ((100 - this.effective)/100));
					},
					fert_treated: function()
					{
						return Math.round(this.fert_att * ((100 - this.effective)/100));
						
					},
					fert_difference: function()
					{
						return Math.round( this.fert_att - this.fert_treated);
					},			
					total_treated: function()
					{
						return (this.total_att - this.fert_difference);
					},
					difference: function()
					{
						return ((this.total_att - this.total_treated));
					}
					
				}
		});