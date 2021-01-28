<template>
	<div>
		<v-text-field
			label="Search"
			v-model="search"
		/>

		<v-data-table :headers="headers" :items="dinos" :search="search">
			<template v-slot:item.dododex="{ item }">
				<a :href="getDododexLink(item)" target="_blank">
					<v-icon>mdi-link</v-icon>
				</a>
			</template>
		</v-data-table>
	</div>
</template>

<script lang="ts">
import {Vue, Component, Prop} from "vue-property-decorator";
import {DataTableHeader} from "vuetify";

@Component({})
export default class TamedTable extends Vue {
	@Prop({ type: Array, required: true, })
	dinos!: any[];

	headers: DataTableHeader[] = [
		{ text: "Map", value: "map", sortable: true },
		{ text: "Type", value: "type", sortable: true },
		{ text: "Sex", value: "sex", sortable: true},
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
