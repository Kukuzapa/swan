<template>
	<div class="row mt-4">

		<b-modal ref="my-modal" :title="modalTitle" hide-footer>
			<div v-if="operation == 'del'">
				<p v-for="book in selectedBooks" :key="book.id">{{ book.name }}, автор - {{ book.author }}</p>
			</div>

			<b-form v-else>
				<b-form-group label="Автор">
					<b-form-input v-model="form.author"></b-form-input>
				</b-form-group>
				<b-form-group label="Название">
					<b-form-input v-model="form.name"></b-form-input>
				</b-form-group>
				<b-form-group label="Жанр">
					<b-form-input v-model="form.genre"></b-form-input>
				</b-form-group>
				<b-form-group label="Год выпуска">
					<b-form-input v-model="form.year"></b-form-input>
				</b-form-group>
			</b-form>

			<b-alert v-if="response.success" show variant="success">{{ response.success }}</b-alert>
			<b-alert v-if="response.error" show variant="danger">{{ response.error }}</b-alert>
			<b-button block variant="secondary" @click="sendForm( operation )">Подтвердить</b-button>
		</b-modal>

		<div class="col-4">
			<b-button block variant="secondary" @click="addBook">add</b-button>
		</div>

		<div class="col-4">
			<b-button block variant="secondary" @click="updBook" :disabled="this.selectedBooks.length != 1">upd</b-button>
		</div>

		<div class="col-4">
			<b-button block variant="secondary" @click="delBook" :disabled="this.selectedBooks.length == 0">del</b-button>
		</div>

		<div class="col-12 mt-4">
			<b-pagination
				v-model="currentPage"
				:total-rows="rows"
				:per-page="perPage"
			></b-pagination>
		</div>
	
		<div class="col-12">
			<b-table striped hover :items="list" :fields="fields" selectable select-mode="multi" selectedVariant="success" @row-selected="rowSelected"></b-table>
		</div>
	</div>
</template>

<script>
const axios = require('axios');
//const uri = 'http://code/';
const uri = '';

export default {
	data: function(){
		return {
			list: null,

			fields: [ 
				{ key: 'author', label: 'Автор', class: 'w-25' },
				{ key: 'name', label: 'Название', class: 'w-45' },
				{ key: 'genre', label: 'Жанр', class: 'w-20' },
				{ key: 'year', label: 'Год', class: 'w-10' }, ],
				
			selectedBooks: [],
			
			currentPage: 1,
			rows: null,
			perPage: 10,

			form: {
				author: '',
				name: '',
				genre: '',
				year: '',
				id: ''
			},

			modalTitle: '',
			operation: '',

			response: []
		}
	},

	created: function(){
		this.getList();
	},

	watch: {
		currentPage: function(){
			this.getList();
		}
	},

	methods:{
		//получаем активную страницу
		getList: function(){
			axios.get( uri + 'index.php/pages/library/' + this.perPage + '/' + this.currentPage ).then( response => {
				this.rows = response.data.count;
				this.list = response.data.list;
  			})
		},

		//формирование массива отмеченных строк
		rowSelected: function( items ){
			this.selectedBooks = items;
		},

		//Отправка формы
		sendForm: function( oper ){
			let request = {
				author: this.form.author,
				name: this.form.name,
				genre: this.form.genre,
				year: this.form.year
			}
			  
			if ( oper == 'upd' ){
				request.id = this.form.id;
			}
			
			if ( oper == 'del'){
				request = { deleted: [] };
				this.selectedBooks.forEach(function(book) {
					request.deleted.push( book.id );
				});
			}

			axios.post( uri + 'index.php/pages/' + oper + '/', request ).then( response => {
				this.response = response.data;
				if ( this.response.success ){
					this.getList();
				}
  			});
		},

		addBook: function(){
			this.response = [];
			this.operation = 'add';
			this.form.author = '';
			this.form.name = '';
			this.form.genre = '';
			this.form.year = '';
			this.modalTitle = 'Добавление новой книги';

			this.$refs['my-modal'].show();
		},

		updBook: function(){
			this.response = [];
			this.operation = 'upd';
			this.form.author = this.selectedBooks[0].author;
			this.form.name = this.selectedBooks[0].name;
			this.form.genre = this.selectedBooks[0].genre;
			this.form.year = this.selectedBooks[0].year;
			this.form.id = this.selectedBooks[0].id;
			this.modalTitle = 'Редактирование информации о книге';

			this.$refs['my-modal'].show();
		},

		delBook: function(){
			this.response = [];
			this.operation = 'del';
			this.modalTitle = 'Удаление книг';

			this.$refs['my-modal'].show();
		}
	}
}

</script>
