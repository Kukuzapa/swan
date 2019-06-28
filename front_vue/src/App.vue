<template>
	<div id="app" class="container">
		<div class="row">
			<div class="col-12">
				<h1 class="text-center">Тестовое задание</h1>
			</div>
			
			<div class="col-12">
				<b-tabs content-class="mt-3">
					<b-tab title="Книги" active><books></books></b-tab>
					<b-tab title="Тестовое задание"><about></about></b-tab>
				</b-tabs>
			</div>
		</div>
	</div>
</template>

<script>
import Books from './components/books.vue'
import About from './components/about.vue'

export default {
	name: 'app',
	components: { About, Books }
}
</script>