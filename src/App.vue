<template>
	<v-app>
		<v-container>
			<v-card>
				<v-card-title>
					<v-tabs v-model="tab">
						<v-tab key="table">Table</v-tab>
						<v-tab key="mapTheIsland">The Island</v-tab>
						<v-tab key="mapRagnarok">Ragnarok</v-tab>
					</v-tabs>
				</v-card-title>
				<v-card-text>
					<v-tabs-items v-model="tab">
						<v-tab-item key="table">
							<TamedTable :dinos="items" />
						</v-tab-item>
						<v-tab-item key="mapTheIsland">
							<Map :dinos="items" map="TheIsland" />
						</v-tab-item>
						<v-tab-item key="mapRagnarok">
							<Map :dinos="items" map="Ragnarok" />
						</v-tab-item>
					</v-tabs-items>
				</v-card-text>
			</v-card>
		</v-container>
	</v-app>
</template>

<script lang="ts">
	import { Component, Vue } from "vue-property-decorator";
	import Map from "./components/Map.vue";
	import TamedTable from "@/components/TamedTable.vue";

	@Component({
		components: { TamedTable, Map },
	})
	export default class App extends Vue {
		tab = "table";
		items: any[] = [];

		async mounted() {
			const [files, files2] = await Promise.all([
				fetch(`https://ark.virant.fr/tamed.php`).then((r) => r.json()),
				fetch(`https://ark.virant.fr/tamed2.php`).then((r) => r.json()),
			]);

			this.items = Array.from(Object.keys(files))
				.map((k) => files[k])
				.reduce((arr: any[], cur: any[]) => {
					arr.push(
						...cur
							.filter((c) => c.type && c.wildLevels)
							.map((d) => Object.assign(d, { map: "TheIsland" }))
					);
					return arr;
				}, [] as any[]);
			this.items.push(
				...Array.from(Object.keys(files2))
					.map((k) => files2[k])
					.reduce((arr: any[], cur: any[]) => {
						arr.push(
							...cur
								.filter((c) => c.type && c.wildLevels)
								.map((d) => Object.assign(d, { map: "Ragnarok" }))
						);
						return arr;
					}, [] as any[])
			);

			const noSexDinos = [
				"Dung Beetle",
				"Titanoboa",
				"Achatina",
				"Queen Bee",
				"Titanosaur",
			];
			this.items.forEach((item, idx, items) => {
				if (item.female) {
					items[idx].sex = "F";
				} else if (noSexDinos.includes(item.type)) {
					items[idx].sex = "X";
				} else {
					items[idx].sex = "M";
				}
			});
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
