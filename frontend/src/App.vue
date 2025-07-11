<template>
	<div id="app">

		<div class="flex" style="margin-bottom: 40px">
			<button @click="modalCreateItem = true">Crear Item</button>
		</div>

		<div v-for="item in items" :key="item.id"
			:class="{ 'card selectable mb10 flex': true, 'red': item.quantity < 0 }" @click="modalOptions = item">
			<div class="flex-fill">{{ item.title }}</div>
			<template v-if="item.unit">
				<div>{{ item.quantity }} {{ item.unit }}</div>
			</template>
			<template v-else>
				<div>{{ item.quantity }}</div>
			</template>
		</div>

		<items-summary :items="items" :onWarning="Warning"></items-summary>

		<modal v-model="modalOptions">
			<div class="flex column" v-if="modalOptions">
				<button @click="modalAddOrRemoveItemStock = true" class="mb10">Agregar o quitar {{ modalOptions.title
				}}</button>
				<button @click="openItemDetails(modalOptions.id)" class="mb10">Ver transacciones de {{
					modalOptions.title
				}}</button>
				<button @click="deleteItem">Eliminar item {{ modalOptions.title }}</button>
			</div>
		</modal>

		<item-details ref="itemDetails" :item="modalOptions"></item-details>

		<modal v-model="modalAddOrRemoveItemStock">
			<div class="flex column" v-if="modalOptions">
				<h1 class="mb10">Agregar o quitar stock</h1>
				<input v-model="modalOptions.title" type="text" placeholder="Nombre del item" class="mb10" disabled>
				<input v-model="addStockValue" type="number" placeholder="Cantidad" class="mb10">
				<button @click="addStock" class="mb10">Actualizar</button>
			</div>
		</modal>

		<modal v-model="modalCreateItem">
			<div class="flex column">
				<h1 class="mb10">Crear Item</h1>
				<input v-model="newItem.title" type="text" placeholder="Nombre del item" class="mb10">
				<input v-model="newItem.unit" type="text" placeholder="Unidad de medida" class="mb10">
				<button @click="createNewItem" class="mb10">Crear</button>
			</div>
		</modal>

		<modal v-model="modalWarning">
			<div v-if="modalWarning">Hay {{ modalWarning.count }} items con bajo stock</div>
		</modal>
	</div>
</template>

<script>
import Modal from './components/Modal.vue';
import ItemsSummary from './components/ItemsSummary.vue';
import ItemDetails from './components/ItemDetails.vue';

export default {
	name: 'App',
	components: {
		Modal, ItemsSummary, ItemDetails
	},
	data() {
		return {
			items: [],
			modalOptions: null,
			modalWarning: null,
			modalCreateItem: false,
			modalAddOrRemoveItemStock: false,
			addStockValue: 0,
			newItem: {
				title: '',
				unit: ''
			}
		}
	},
	mounted() {
		this.getAllItems();
	},
	methods: {
		Warning(itemsNumber) {
			this.modalWarning = { count: itemsNumber };
		},
		getAllItems() {
			axios.get('items').then(res => {
				console.log(res.data);
				this.items = res.data;
			}).catch(err => {
				console.log('Error message GET all items: ', err);
			});
		},
		createNewItem() {
			console.log({ title: this.newItem.title, unit: this.newItem.unit });

			if (!this.newItem.title || this.newItem.title.trim() === '') {
				alert('El título es obligatorio.');
				return;
			}

			if (!this.newItem.unit || this.newItem.unit.trim() === '') {
				alert('La unidad es obligatoria.');
				return;
			}

			axios.put('item', {
				title: this.newItem.title,
				unit: this.newItem.unit
			}).then(res => {
				console.log(res.data);
				this.modalCreateItem = false;
				this.items = res.data;
				this.newItem = { title: '', unit: '' };
				this.getAllItems()
			}).catch(err => {
				console.log('Error message create new item: ', err);
			});
		},
		deleteItem() {
			axios.delete(`item/${this.modalOptions.id}`).then(res => {
				console.log(res.data);
				this.modalOptions = null;
				this.getAllItems();
			}).catch(err => {
				console.log('Error message delete item: ', err);
			});
		},
		addStock() {
			if (!this.addStockValue || isNaN(this.addStockValue) || Number(this.addStockValue) === 0) {
				alert('La cantidad debe ser un número distinto de cero.');
				return;
			}
			if (this.addStockValue == 0) {
				return;
			}

			const transactions = {
				id_item: this.modalOptions.id,
				quantity: Number(this.addStockValue),
				details: {
					from: Number(this.modalOptions.quantity),
					to: Number(this.modalOptions.quantity) + Number(this.addStockValue)
				}
			}
			console.log(transactions);
			axios.put(`item/${this.modalOptions.id}/stock`, {
				transactions: transactions
			}).then(res => {
				console.log(res.data);
				this.modalAddOrRemoveItemStock = false;
				this.modalOptions = null;
				this.addStockValue = 0;
				this.getAllItems();
			}).catch(err => {
				console.log('Error message add stock: ', err);
			});
		},
		openItemDetails(id_item) {
			this.$refs.itemDetails.Open(id_item)
		}
	}
}
</script>

<style>
html,
body {
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
	box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
	background: white;

}

.card.selectable {
	cursor: pointer;
}

.card.selectable:hover {
	outline: 2px solid #2c3e50;
}
</style>
