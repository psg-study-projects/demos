<template>
<b-container fluid id="wrap-about_services" class="debug-border template-wrap">

    <b-row class="h-100 justify-content-center tag-background_image">
        <b-col md="7" class="my-auto">

            <b-row id="page-heading" class="supercrate">
                <b-col>
                    <h2> <img v-in-viewport.once src="@/assets/img/gtc-logo-bug.png" class="img-fluid" alt="GTC Logo"> <span v-in-viewport.once>Services</span></h2>
                </b-col>
            </b-row>

            <b-row class="supercrate">
                <b-col>
                    <h1 v-in-viewport.once>What we provide</h1>
                </b-col>
            </b-row>

            <b-row id="img-services" class="supercrate justify-content-center">
                <b-col class="d-none d-md-block" md="3">
                    <img v-in-viewport.once class="img-fluid" src="@/assets/img/icons8-Microphone-100.png" alt="microphone">
                    <h6 v-in-viewport.once>Radio</h6>
                </b-col>
                <b-col class="d-none d-md-block" md="3">
                    <img v-in-viewport.once class="img-fluid" src="@/assets/img/icons8-Monitor-100.png" alt="monitor">
                    <h6 v-in-viewport.once>TV</h6>
                </b-col>
                <b-col class="d-none d-md-block" md="3">
                    <img v-in-viewport.once class="img-fluid" src="@/assets/img/icons8-Eye-100.png" alt="eye">
                    <h6 v-in-viewport.once>Experimental</h6>
                </b-col>
                <b-col class="d-none d-md-block" md="3">
                    <img v-in-viewport.once class="img-fluid" src="@/assets/img/icons8-Resolution-100.png" alt="resolution-100">
                    <h6 v-in-viewport.once>Digital</h6>
                </b-col>

                <b-col class="tag-services_vertical d-md-none">
                    <b-row>
                        <b-col>
                            <img v-in-viewport.once src="@/assets/img/icons8-Microphone-100.png" alt="microphone">
                        </b-col>
                        <b-col>
                            <span v-in-viewport.once class="">Radio</span>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col>
                            <img v-in-viewport.once src="@/assets/img/icons8-Monitor-100.png" alt="monitor">
                        </b-col>
                        <b-col>
                            <span v-in-viewport.once>TV</span>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col>
                            <img v-in-viewport.once src="@/assets/img/icons8-Eye-100.png" alt="eye">
                        </b-col>
                        <b-col>
                            <span v-in-viewport.once>Experimental</span>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col>
                            <img v-in-viewport.once src="@/assets/img/icons8-Resolution-100.png" alt="resolution-100">
                        </b-col>
                        <b-col>
                            <span v-in-viewport.once>Digital</span>
                        </b-col>
                    </b-row>
                </b-col>

            </b-row>

            <b-row id="services-details" class="supercrate">
                <b-col class="component-footer">
                    <p v-in-viewport.once='100'>Global Tour Creatives is widely regarded as the best entertainment marketing production agency in the world. GTC leads in creative, customer service and responsiveness. We offer clients around-the-clock creative production services from our digital hub in Los Angeles, USA and our European studio in Dublin, Ireland. With over 250 voice talent on our roster fluent in over 115 languages; we are the only "Global" agency representing new and established artists.</p>
                </b-col>
            </b-row>

        </b-col>
    </b-row>

</b-container>
</template>

<script>
import Vue from 'vue';
import inViewportDirective from 'vue-in-viewport-directive';
Vue.directive('in-viewport', inViewportDirective);

export default {
    name: 'Services'
}
</script>

<style scoped lang="scss">
@import '~bootstrap/scss/_functions';
@import '~bootstrap/scss/_variables';
@import '~bootstrap/scss/mixins/_breakpoints';

#img-services .col:last-child {
    border-right: none;
}
.supercrate {
    text-align: center;
}

@include media-breakpoint-down(sm) {
    h1 {
        font-size: 1.6em;
        margin-bottom: 0;
    }
    #page-heading {
        margin-bottom: 1em;
    }
    #img-services {
        margin-top: 1.2em;
        margin-bottom: 1.2em;
    }
    #img-services .tag-services_vertical img {
        display: inline-block;
        width: 4em;
    }
    #img-services .tag-services_vertical span {
        display: inline-block;
        margin-top: 1.25em; // rough vertical centering
    }
    #services-details  p {
        text-align: left;
        width: inherit; // override 'about'
    }
}

@include media-breakpoint-up(md) {
    #page-heading {
        margin-bottom: 3em;
    }
    #img-services {
        margin-top: 5em;
        margin-bottom: 5em;
    }
    #img-services .col {
        border-right: solid 1px rgba(255, 255, 255, 0.5);
    }
    #services-details  p {
        font-size: 1.3em;
        width: inherit; // override 'about'
    }
}

// =========================
// Transitions
// =========================

h1 {
    transform: translateY(0.7em);
    opacity: 0;
    transition: all 1.9s;
    transition-delay: 1.0s;
}
h1.in-viewport {
    transform: translateY(0);
    opacity: 1;
}
#img-services img {
    transform: translateY(0.7em);
    opacity: 0;
    transition: all 1.9s;
    transition-delay: 1.5s;
}
#img-services img.in-viewport {
    transform: translateY(0);
    opacity: 1;
}
#img-services h6,
#img-services span {
    transform: scale(0.75);
    opacity: 0;
    transition: all 0.5s;
}
#img-services h6.in-viewport,
#img-services span.in-viewport {
    transform: scale(1);
    opacity: 1;
}
</style>
