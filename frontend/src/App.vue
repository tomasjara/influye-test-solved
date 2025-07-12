<template>
	<div id="app">

		<h1>Inventario de Items</h1>

		<div class="flex">
			<button @click="openModalCreateItem()">Crear Item</button>
		</div>

		<items-summary :items="items" :onWarning="Warning"></items-summary>

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


		<modal v-model="modalOptions">
			<div class="flex column" v-if="modalOptions">
				<h2>{{ modalOptions.title }}</h2>
				<button @click="openModalAddStock()" class="mb10">Agregar o quitar stock</button>
				<button @click="openItemDetails(modalOptions.id)" class="mb10">Ver transacciones</button>
				<button class="button-red" @click="deleteItem">Eliminar item</button>
			</div>
		</modal>

		<item-details ref="itemDetails" :item="modalOptions"></item-details>

		<modal v-model="modalAddOrRemoveItemStock">
			<div class="flex column " style="gap: 10px;" v-if="modalOptions">
				<h2 class="mb10">Agregar o quitar stock</h2>
				<div style="display: flex; gap: 10px;">
					<input v-model="modalOptions.title" type="text" placeholder="Nombre del item" class="mb10" disabled>
					<div style="display: flex; gap: 10px; margin-bottom: 10px; justify-items: center;">
						<button @click="addStockValue -= 1">-</button>
						<input ref="addStockInput" v-model.number="addStockValue" type="number" placeholder="Cantidad"
							class="">
						<button @click="addStockValue += 1">+</button>
					</div>
				</div>
				<p v-if="addStockValueError" class="error">{{ addStockValueError }}</p>
				<button @click="addStock" class="mb10">Actualizar</button>
			</div>
		</modal>

		<modal v-model="modalCreateItem">
			<div class="flex column">
				<h1 class="mb10">Crear Item</h1>
				<input ref="newItemTitleInput" v-model="newItem.title" type="text" placeholder="Nombre del item"
					class="mb10">
				<input v-model="newItem.unit" type="text" placeholder="Unidad de medida" class="mb10">
				<p v-if="newItemError" class="error">{{ newItemError }}</p>
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
			},
			newItemError: '',
			addStockValueError: ''
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
				// console.log(res.data);
				this.items = res.data;
			}).catch(err => {
				console.log('Error message GET all items: ', err);
			});
		},
		openModalCreateItem() {
			this.newItemError = '';
			this.modalCreateItem = true;
			this.$nextTick(() => {
				this.$refs.newItemTitleInput.focus();
			});
		},
		openModalAddStock() {
			this.addStockValueError = '';
			this.modalAddOrRemoveItemStock = true
			this.$nextTick(() => {
				this.$refs.addStockInput.focus();
			});
		},
		createNewItem() {
			if ((!this.newItem.title || this.newItem.title.trim() === '') &&
				(!this.newItem.unit || this.newItem.unit.trim() === '')) {
				this.newItemError = 'Los dos campos son obligatorios.';
				return;
			}

			if (!this.newItem.title || this.newItem.title.trim() === '') {
				this.newItemError = 'El título es obligatorio.';
				return;
			}

			if (!this.newItem.unit || this.newItem.unit.trim() === '') {
				this.newItemError = 'La unidad es obligatoria.';
				return;
			}

			axios.put('item', {
				title: this.newItem.title,
				unit: this.newItem.unit
			}).then(res => {
				// console.log(res.data);
				this.newItemError = '';
				this.modalCreateItem = false;
				this.items = res.data;
				this.newItem = { title: '', unit: '' };
				this.getAllItems()
			}).catch(err => {
				this.newItemError = '';
				console.log('Error message create new item: ', err);
			});
		},
		deleteItem() {
			axios.delete(`item/${this.modalOptions.id}`).then(res => {
				// console.log(res.data);
				this.modalOptions = null;
				this.getAllItems();
			}).catch(err => {
				console.log('Error message delete item: ', err);
			});
		},
		addStock() {
			if (!this.addStockValue || isNaN(this.addStockValue) || Number(this.addStockValue) === 0) {
				this.addStockValueError = 'La cantidad debe ser un número distinto de cero.';
				return;
			}
			this.addStockValueError = '';
			const transactions = {
				id_item: this.modalOptions.id,
				quantity: Number(this.addStockValue),
				details: {
					from: Number(this.modalOptions.quantity),
					to: Number(this.modalOptions.quantity) + Number(this.addStockValue)
				}
			}
			axios.put(`item/${this.modalOptions.id}/stock`, {
				transactions: transactions
			}).then(res => {
				// console.log(res.data);
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

h1 {
	text-align: center;
}

.error {
	color: red;
	font-size: 0.9rem;
	margin-top: 5px;
}

h2 {
	text-align: center;
	margin: 0;
	margin-bottom: 13px;
	font-size: 1.5rem;
}

button {
	padding: 8px 16px;
	border-radius: 6px;
	border: 1px solid #e2e8f0;
	cursor: pointer;
	background-color: #0f172a;
	color: white;
	transition: background-color 0.3s ease;
	text-align: center;

	&:hover {
		background-color: #2e2e2e;
	}
}

.button-red {
	background-color: #dc2626;

	&:hover {
		background-color: #df4848;
	}
}

input {
	border: 1px solid #ccc;
	border-radius: 5px;
	padding: 10px;
	width: 100%;
	box-sizing: border-box;
}

#app {
	font-family: Avenir, Helvetica, Arial, sans-serif;
	color: #2c3e50;
	padding: 20px;
	max-width: 500px;
	margin: 0 auto;
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
	outline: 1px solid #2c3e5081;
	transform: scale(1.01);
}
</style>
