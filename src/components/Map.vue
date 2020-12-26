<template>
    <svg ref="svg" width="1024px" height="1024px">
        <rect class="overlay" width="1024px" height="1024px"/>
        <g ref="g">
            <image width="1024px" height="1024px" xlink:href="../assets/The_Island_Topographic_Map.jpg" />
        </g>
    </svg>
</template>

<script lang="ts">
import {Vue, Component, Prop, Watch} from "vue-property-decorator";
import * as d3 from "d3";

@Component({})
export default class Map extends Vue {
    @Prop({ type: Array, required: true, })
    dinos!: any[];

    position: [number, number] = [0, 0];
    scale: number = 1;

    originalHtml = "";

    mounted() {
        this.originalHtml = (this.$refs.g as Element).innerHTML;
        this._watchDinos();
    }

    @Watch("dinos", {deep: true})
    _watchDinos() {
        (this.$refs.g as Element).innerHTML = this.originalHtml;
        const g = d3.select(this.$refs.g as Element);
        g.call(d3.zoom().scaleExtent([1, 8]).on("zoom", ev => {
            this.position = [ev.transform.x, ev.transform.y];
            this.scale = ev.transform.k;
            g.attr("transform", `translate(${this.position}) scale(${this.scale})`);
        }));

        const x = d3.scaleLinear().domain([-40 * 8500, 40 * 8500]).range([0, 1024]);
        const y = d3.scaleLinear().domain([-40 * 8500, 40 * 8500]).range([0, 1024]);
        // g.append("g").call(d3.axisBottom(y));

        const levels = d3.scaleLinear(["#a2f0ff","#ffa600",]).domain([1, 200]);
          // Add dots
        g.append('g')
            .selectAll("dot")
            .data(this.dinos)
            .enter()
            .append("circle")
                .attr("cx", d => x(d.location.x))
                .attr("cy", d => y(d.location.y))
                .attr("r", d => 1)
                .attr("stroke", "black")
                .attr("stroke-width", "0.2")
                .style("fill", d => levels(d.baseLevel))
                .on("click", (_, d) => alert(`${d.type}, ${d.name || "<noname>"} ${d.baseLevel + (d.extraLevel || 0)}`))
            ;
    }
}
</script>

<style type="text/css">
.overlay {
  fill: none;
  pointer-events: all;
}
</style>
