<template>
	<v-app>
		<v-container>
			<v-card>
				<v-card-title>Tamed</v-card-title>
				<v-card-text>
					<v-text-field
						label="Search"
						v-model="search"
					/>

					<v-data-table :headers="headers" :items="items" :search="search">
						<template v-slot:item.dododex="{ item }">
							<a :href="getDododexLink(item)" target="_blank">
								<v-icon>mdi-link</v-icon>
							</a>
						</template>
					</v-data-table>
				</v-card-text>
			</v-card>
		</v-container>
	</v-app>
</template>

<script lang="ts">
	import { Component, Vue } from "vue-property-decorator";
	import HelloWorld from "./components/HelloWorld.vue";
	import type { DataTableHeader } from "vuetify";

	@Component({})
	export default class App extends Vue {
		headers: DataTableHeader[] = [
			{ text: "Type", value: "type", sortable: true },
			{ text: "Name", value: "name", sortable: true },
			{ text: "Wild level", value: "baseLevel", sortable: true },
			{ text: "Extra level", value: "extraLevel", sortable: true },
			{ text: "Health", value: "wildLevels.health", sortable: true },
			{ text: "Stamina", value: "wildLevels.stamina", sortable: true },
			{ text: "Oxygen", value: "wildLevels.oxygen", sortable: true },
			{ text: "Food", value: "wildLevels.food", sortable: true },
			{ text: "Weight", value: "wildLevels.weight", sortable: true },
			{ text: "Melee", value: "wildLevels.melee", sortable: true },
			{ text: "Speed", value: "wildLevels.speed", sortable: true },
			{ text: "Dododex", value: "dododex", sortable: false },
		];
		search: string = "";

		items: any[] = [];

		async mounted() {
			const files = await fetch(`https://ark.virant.fr/tamed.php`).then(r => r.json());
			this.items = Array.from(Object.keys(files))
				.map((k) => files[k])
				.reduce((arr: any[], cur: any[]) => {
					arr.push(...cur.filter(c => c.type && c.wildLevels));
					return arr;
				}, [] as any[]);
		}

		getDododexLink(item: any) {
			// https://www.dododex.com/stat-calculator/argentavis/22?&ph=1&ps=2&po=3&pf=4&pw=5&pd=6
			const arr = [
				`ph=${item.wildLevels.health ?? 0}`,
				`ps=${item.wildLevels.stamina ?? 0}`,
				`po=${item.wildLevels.oxygen ?? 0}`,
				`pf=${item.wildLevels.food ?? 0}`,
				`pw=${item.wildLevels.weight ?? 0}`,
				`pd=${item.wildLevels.melee ?? 0}`,
			];
			return `https://www.dododex.com/stat-calculator/${item.type.toLocaleLowerCase()}/${item.baseLevel}?${arr.join("&")}`;
		}
	}
</script>

<style>
	#app {
		font-family: Avenir, Helvetica, Arial, sans-serif;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		text-align: center;
		color: #2c3e50;
		margin-top: 60px;
	}
</style>
