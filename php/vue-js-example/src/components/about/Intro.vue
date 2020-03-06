<template>
<b-container fluid id="wrap-about_intro" class="debug-border template-wrap">

    <b-row class="h-100 tag-background_image">
        <b-col class="my-auto">
            <div id="page-heading" class="supercrate">
                <h2><img src="@/assets/img/logo.png" class="img-fluid" alt="GTC Logo"></h2>
            </div>
            <div class="supercrate">
                <h1>Digital<br />Marketing</h1>
                <h3 class="condensed">Unlike anything you could imagine</h3>
            </div>
        </b-col>
    </b-row>

</b-container>
</template>

<script>
export default {
    name: 'Intro',
    props: {
        msg: String
    },
    computed: {
    },
    data () {
        return {
            foo: 0
        }
    },
    methods: {
    }
}
</script>

<style scoped lang="scss">
@import '~bootstrap/scss/_functions';
@import '~bootstrap/scss/_variables';
@import '~bootstrap/scss/mixins/_breakpoints';

.tag-background_image {
    background-image: url(./assets/img/mountains-1.jpg);
}
.template-wrap .supercrate {
    text-align: center;
}

h1 {
    margin-top: 3rem;
    margin-bottom: 3rem;
    letter-spacing: 2px;
    font-weight: 400;
    line-height: 1em;
}
h3 {
    font-size: 1.17em;
}

@include media-breakpoint-up(xs) {
    h1 {
        font-size: 3em;
    }
    h2 > img {
        width: 6.5em;
    }
}

@include media-breakpoint-up(md) {
    h1 {
        font-size: 5em;
    }
    h2 > img {
        width: 7.9em;
    }
}

@include media-breakpoint-up(lg) {
    h1 {
        font-size: 5em;
    }
}
</style>
