<template>
	<div>
		<v-text-field
			label="Search"
			v-model="search"
		/>
		<div ref="map" style="border: 1px dotted blue; min-height: 80vh;"/>
	</div>
</template>

<script lang="ts">
import {Vue, Component, Prop, Watch} from "vue-property-decorator";
import * as d3 from "d3";
import {circleMarker, CRS, imageOverlay, latLng, map as LeafletMap, marker} from "leaflet";
// @ts-ignore
import imgMapTheIsland from "../assets/The_Island_Topographic.jpg";
// @ts-ignore
import imgMapRagnarok from "../assets/Ragnarok_Map.png";

@Component({})
export default class Map extends Vue {
	@Prop({ type: String, required: true, })
	map!: "TheIsland"|"Ragnarok";
	@Prop({ type: Array, required: true, })
	dinos!: any[];

	leafletMap: any = null;
	search: string = "";
	markers: any[] = [];

	get filteredDinos() {
		const dinos = this.dinos.filter(d => d.map === this.map);
		if (!this.search)
			return dinos;
		const regex = new RegExp(this.search.replaceAll(/([a-z0-9_-]+)/gi, s => `.*${s}.*`), "i");
		return dinos.filter(d => {
			if (regex.test(d.name))
				return true;
			if (regex.test(d.type))
				return true;
			return false;
		});
	}

	mounted() {
		this.leafletMap = LeafletMap(this.$refs.map as HTMLDivElement, {
			crs: CRS.Simple,
			minZoom: 2,
			maxZoom: 10,
		});
		const bounds: [[number, number], [number, number]] = [[0, 0], [100, 100]];
		this.leafletMap.fitBounds(bounds);
		imageOverlay(this.map === "TheIsland" ? imgMapTheIsland : imgMapRagnarok, bounds).addTo(this.leafletMap);

		this._watchDinos();
	}

	@Watch("filteredDinos", {deep: true})
	_watchDinos() {
		const levels = d3.scaleLinear(["#a2f0ff", "#ffa600",]).domain([100, 300]);

		function arkToLatLng(loc: any) {
			return latLng({
				lat: ((100 - loc.lat) - 0.52 - 50) * 0.9625 + 50,
				lng: (loc.lon + 0.9 - 50) * 0.99 + 50,
			});
		}

		for (const m of this.markers)
			m.remove();

		for (const dino of this.filteredDinos) {
			const lat = Math.round(dino.location.lat * 10) / 10;
			const lon = Math.round(dino.location.lon * 10) / 10;

			this.markers.push(
				circleMarker(arkToLatLng(dino.location), {
					fill: true,
					fillColor: levels(dino.baseLevel),
					fillOpacity: 0.9,
					weight: 1,
					radius: Math.max(8, dino.baseLevel / 20),
				})
					.addTo(this.leafletMap)
					.bindPopup(`${dino.type}, ${dino.name || "<noname>"} ${dino.baseLevel + (dino.extraLevel || 0)} (${lat}, ${lon})`)
			);
		}
	}
}
</script>

<style type="text/css">
.overlay {
	fill: none;
	pointer-events: all;
}
</style>
