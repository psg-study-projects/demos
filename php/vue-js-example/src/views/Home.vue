<template>
    <b-container fluid id="page-home" class="home bv-example-row">
        <b-row>
            <b-col>
                <TourList msg="foo"/>
            </b-col>
        </b-row>
        <b-row v-if="!isPreviewVisible">
            <b-col>
                <VideoSlider msg="foo"/>
            </b-col>
        </b-row>
        <b-row v-if="!isPreviewVisible">
            <b-col>
                <GtcServices msg="foo"/>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
// @ is an alias to /src
//import PageHeader from '@/components/PageHeader.vue'
import TourList from '@/components/home/TourList.vue'
import VideoSlider from '@/components/home/VideoSlider.vue'
import GtcServices from '@/components/home/GtcServices.vue'
import EventBus from '@/main.js'

export default {
    name: 'home',
    data() {
        return {
            isPreviewVisible : false,
        }
    },
    methods: {
        openPreview(e) {
            this.isPreviewVisible = true;
        },
        closePreview(e) {
            this.isPreviewVisible = false;
        },
    },
    created() {
        EventBus.$on( 'open-preview', e => this.openPreview(e) );
        EventBus.$on( 'close-preview', e => this.closePreview(e) );
    },
    components: {
        //PageHeader,
        TourList,
        VideoSlider,
        GtcServices
    }
}
</script>

<style lang="scss">
</style>

<style lang="scss" scoped>
#app {
  /*
  height: inherit;
  background: #6e6e6e;
  display: flex;
  flex-direction: column;
  align-items: center;
  -webkit-align-items: center;
  justify-content: center;
  -webkit-justify-content: center;
  */
}
</style>
