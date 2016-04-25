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