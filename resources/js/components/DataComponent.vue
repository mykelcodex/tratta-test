<template>
	<div>
		<div class="mt-32">
			<div class="max-w-xxl mx-auto px-6">
				<div class="max-w-2xl mx-auto">
					<form>
						<div class="grid grid-cols-10 gap-4">
							<div class="col-span-10">
								<label for="account_number" class="block text-sm font-medium text-gray-700">Account number</label>
								<div class="mt-1">
									<input v-model="form.accountSid" type="text" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Account Number">
								</div>
							</div>
							<div class="col-span-5">
								<label for="start_date" class="block text-sm font-medium text-gray-700">Start date</label>
								<div class="mt-1">
									<input v-model="form.StartDate" type="date" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Start Date">
								</div>
							</div>
							<div class="col-span-5">
								<label for="end_date" class="block text-sm font-medium text-gray-700">End date</label>
								<div class="mt-1">
									<input v-model="form.EndDate" type="date" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Start Date">
								</div>
							</div>
							<div class="col-span-10">
								<button @click.prevent="getData" 
									:class="['bg-blue-500 w-full text-white mx-auto sm:mt-6 py-2 rounded-md', loading ? 'bg-opacity-75' : 'hover:bg-opacity-75']"
									:disabled="loading"
								>
									<loading v-if="loading"/>
									<span v-else>Fetch</span>
								</button>
							</div>
							<div class="col-span-5">
								<label for="call_unit_fee" class="block text-sm font-medium text-gray-700">Call unit fee</label>
								<div class="mt-1">
									<input v-model="call_unit_fee" type="text" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Call Unit Fee">
								</div>
							</div>
							<div class="col-span-5">
								<label for="sms_unit_fee" class="block text-sm font-medium text-gray-700">SMS unit fee</label>
								<div class="mt-1">
									<input v-model="sms_unit_fee" type="text" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="SMS Unit Fee">
								</div>
							</div>

						</div>
						
					</form>

					<div class="mt-12" v-if="filterVoiceData.length > 0 && filterSMSData.length > 0">
						<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
							<div class="bg-white shadow overflow-hidden rounded-md border col-span-1" v-for="voice in filterVoiceData" :key="voice.usage">
								<div role="list" class="divide-y divide-gray-200">
									<div class="px-6 py-4 font-bold">
										Inbound Voice Minutes
									</div>
									<div class="px-6 py-4 flex items-center justify-between">
										<span>Count</span>
										<span>{{ voice.count }}</span>
									</div>
									<div class="px-6 py-4 flex items-center justify-between">
										<span>Usage</span>
										<span>{{ voice.usage }}</span>
									</div>
									<div class="px-6 py-4 flex items-center justify-between">
										<span>Usage Unit</span>
										<span>{{ voice.usage_unit }}</span>
									</div>
									<div class="px-6 py-4 flex items-center justify-between">
										<span>Twilio Price</span>
										<span>{{ voice.price }}</span>
									</div>
									<div class="px-6 py-4 flex items-center justify-between">
										<span>Tratta Price</span>
										<span>{{ tratta_voice_price(voice.usage) }}</span>
									</div>
								</div>
							</div>
							<div class="bg-white shadow overflow-hidden rounded-md border col-span-1" v-for="(sms, index) in filterSMSData" :key="index">
								<div role="list" class="divide-y divide-gray-200">
									<div class="px-6 py-4 font-bold">
										Standard Outbound SMS
									</div>
									<div class="px-6 py-4 flex items-center justify-between">
										<span>Count</span>
										<span>{{ sms.count }}</span>
									</div>
									<div class="px-6 py-4 flex items-center justify-between">
										<span>Usage</span>
										<span>{{ sms.usage }}</span>
									</div>
									<div class="px-6 py-4 flex items-center justify-between">
										<span>Usage Unit</span>
										<span>{{ sms.usage_unit }}</span>
									</div>
									<div class="px-6 py-4 flex items-center justify-between">
										<span>Twilio Price</span>
										<span>{{ sms.price }}</span>
									</div>
									<div class="px-6 py-4 flex items-center justify-between">
										<span>Tratta Price</span>
										<span>{{ tratta_sms_price(sms.usage) }}</span>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import Loading from './partials/Loading.vue'
	export default {
		components:{ Loading },
		data(){
			return{
				form:{
					accountSid:'',
					StartDate:'',
					EndDate:''
				},
				call_unit_fee:'',
				sms_unit_fee:'',
				loading:false,
				data:[],
				index:0
			}
		},
		
		watch:{
			call_unit_fee(value){
				if (/^[0-9]+$/.test(value)) {
					this.tratta_voice_price()
					return
				}
				this.call_unit_fee = ''
				return
			},

			sms_unit_fee(value){
				if (/^[0-9]+$/.test(value)) {
					this.tratta_sms_price()
					return
				}
				this.sms_unit_fee = ''
				return
			}
		},

		computed:{
			filterVoiceData(){
				return this.data.filter(voice=>{
					return voice.category == 'calls-inbound'
				})
				
			},

			filterSMSData(){
				return this.voice = this.data.filter(voice=>{
					return voice.category == 'sms-inbound-longcode'
				})
			},
		},

		methods:{
			async getData(){
				
				if(this.form.accountSid.length == 0){
					alert("Account Number can't be empty")
					return
				}

				this.loading = true
				try {
					let response = await axios.get('/account', { params: this.form })
					this.data = response.data.usage_records
					this.loading = false
				} catch (error) {
					if(error.response.status == '404'){
						alert("Account not found")
						this.loading = false
						return
					}
					alert("Something went wrong, try again")
					this.loading = false
				}
			},

			tratta_voice_price(usage){
				return this.call_unit_fee ? this.call_unit_fee * usage : 0
			},

			tratta_sms_price(usage){
				return this.sms_unit_fee ? this.sms_unit_fee * usage : 0
			}
		},

		mounted() {
			console.log('Component mounted.')
		}
	}
</script>
