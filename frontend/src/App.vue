<template>
  <div id="app">

	<div class="flex" style="margin-bottom: 40px">
		<button>Crear Item</button>
	</div>
	  	
	<div v-for="item in items" :key="item.id" class="card selectable mb10 flex" @click="modalOptions=item">
		<div class="flex-fill">{{item.title}}</div>
		<div>{{item.quantity}}</div>
	</div>
	
	<items-summary></items-summary>

	<modal v-model="modalOptions">
		<div class="flex column" v-if="modalOptions">
			<button class="mb10">Agregar o quitar {{modalOptions.title}}</button>
			<button class="mb10">Ver transacciones de {{modalOptions.title}}</button>
			<button>Eliminar item {{modalOptions.title}}</button>
		</div>
	</modal>

	<modal v-model="modalWarning">
		<div v-if="modalWarning">Hay {{modalWarning.count}} items con bajo stock</div>
	</modal>

  </div>
</template>

<script>
import Modal from './components/Modal.vue';
import ItemsSummary from './components/ItemsSummary.vue';

export default {
	name: 'App',
	components: {
		Modal, ItemsSummary
	},
	data() {
		return {
			items: [],
			modalOptions: null,
			modalWarning: null
		}
	},
	mounted() {
		axios.get('items').then(res => {
			console.log(res.data);
			this.items = res.data;
		}).catch(err => {
			console.log(err);
		});
	},
	methods: {
		Warning(itemsNumber)
		{
			this.modalWarning = {count: itemsNumber};
		}
	}
}
</script>

<style>
html, body {
	background-color: #f7f8f9;
}

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  color: #2c3e50;
  padding: 20px;
}

.flex {
	display: flex;
}
.column {
	flex-direction: column;
}
.flex-fill {
	flex: 1 1 auto;
}

.mb10 {
	margin-bottom: 10px;
}

.secondary-text {
	color: #AAA;
}
.red {
	color: red;
}

.card {
	padding: 10px; 
	border-radius: 10px;
	box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.2);
	background: white;
	
}
.card.selectable {
	cursor: pointer;
}
.card.selectable:hover {
	outline: 2px solid #2c3e50;
}

</style>
