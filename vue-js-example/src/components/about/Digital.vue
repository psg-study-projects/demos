<template>
<b-container fluid id="wrap-about_digital" class="debug-border template-wrap">

    <b-row class="h-100 tag-background_image">
        <b-col class="my-auto">

            <h2> <img v-in-viewport.once src="@/assets/img/gtc-logo-bug-white.png" class="img-fluid" alt="GTC Logo"> <span v-in-viewport.once>Digital</span></h2>
            <h6 class="d-md-none">Digital Video Production</h6>

            <div class="supercrate">
                <span v-in-viewport.once id="img-single">
                    <img id="img-laptop" src="./assets/img/MacBook.png" alt="laptop computer" class="OFF-img-fluid">
                    <div id="box-overlay_screen_img">
                        <img src="./assets/img/tour-30_sec_to_mars.png" alt="laptop screen" class="OFF-img-fluid">
                    </div>
                </span>
                <div class="component-footer">
                    <h6 v-in-viewport.once class="d-none d-md-block">Digital Video Production</h6>
                    <p v-in-viewport.once='100'>Tell your story through high resolution video and dynamic visual effects.</p>
                </div>
            </div>

        </b-col>
    </b-row>

</b-container>
</template>

<script>
import Vue from 'vue';
import inViewportDirective from 'vue-in-viewport-directive';
Vue.directive('in-viewport', inViewportDirective);

export default {
    name: 'Digital'
}
</script>

<style scoped lang="scss">
@import '~bootstrap/scss/_functions';
@import '~bootstrap/scss/_variables';
@import '~bootstrap/scss/mixins/_breakpoints';

h6 {
    color: #d39a9b; // pink-ish
}

//assets/6eed5-GTC091953.png
.tag-background_image {
    background-image: url(./assets/img/img_red02.jpg);
}
.template-wrap .my-auto {
    text-align: center;
}

.template-wrap .supercrate #img-single {
    position: relative;
    display: inline-block;
}

// %TODO: get laptop image to scale vertically...
.template-wrap .supercrate img#img-laptop {
    max-width: 100%;
    //height: auto;
}

.template-wrap .supercrate #img-single #box-overlay_screen_img {
    //border: solid 3px green;
    position: absolute;
}

@include media-breakpoint-down(sm) {
    .template-wrap .supercrate img#img-laptop {
        height: 12em;
        margin-top: 3em;
        margin-bottom: 3em;
    }
    .template-wrap .supercrate #img-single #box-overlay_screen_img {
        top: 3.85em;
        left: 2.10em;
        right: 2.10em;
    }
    .template-wrap .supercrate #img-single #box-overlay_screen_img > img {
        //border: solid 2px yellow;
        // equiv to img-fluid...
        max-width: 100%;
        height: auto;
    }
}

@include media-breakpoint-up(md) {
    .template-wrap .supercrate img#img-laptop {
        height: 45em; // use max!
        margin-top: 3em;
        margin-bottom: 3em;
    }
    .template-wrap .supercrate #img-single #box-overlay_screen_img {
        top: 5.6em;
        left: 6.75em; // 9.65em;
        right: 6.75em; // 9.65em;
        overflow: hidden;
        //width: 100%;
    }
    .template-wrap .supercrate #img-single #box-overlay_screen_img > img {
        //margin-top: 5.6em;
        //border: solid 2px cyan;
        height: 37.5em;
        //width: 100%;
    }
}
@include media-breakpoint-up(lg) {
        //border: solid 2px red;
}

// =========================
// Transitions
// =========================
#img-single {
    transform: translateX(-17em) rotateY(-65deg) rotateX(-10deg) scale(0.85);
    opacity: 0;
    transition: all 3.2s;
}
#img-single.in-viewport {
    opacity: 1;
    transform: scale(1);
}
</style>
