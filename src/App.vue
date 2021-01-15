<template>
	<v-app>
		<v-container>
			<v-card>
				<v-card-title>
					<v-tabs v-model="tab">
						<v-tab key="table">Table</v-tab>
						<v-tab key="map">Map</v-tab>
					</v-tabs>
				</v-card-title>
				<v-card-text>
					<v-tabs-items v-model="tab">
						<v-tab-item key="table">
							<TamedTable :dinos="items"/>
						</v-tab-item>

						<v-tab-item key="map">
							<Map :dinos="items"/>
						</v-tab-item>
					</v-tabs-items>
				</v-card-text>
			</v-card>
		</v-container>
	</v-app>
</template>

<script lang="ts">
import {Component, Vue} from "vue-property-decorator";
import Map from "./components/Map.vue";
import type {DataTableHeader} from "vuetify";
import TamedTable from "@/components/TamedTable.vue";

@Component({
	components: {TamedTable, Map,},
})
export default class App extends Vue {
	tab = "table";
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
