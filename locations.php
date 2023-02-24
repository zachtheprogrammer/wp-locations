<?php
get_header();
?>

<div class="container fluid d-flex flex-column mvh-100">
    <form action="" class="search-form">
        <div class="row align-items-center">
            <div class="col">
                <div class="form-floating">
                    <input type="text" class="form-control" name="location_search" id="location_search">
                    <label for="location_search">Location Search</label>
                </div>
            </div>

            <div class="col">
                <div class="form-floating">
                    <select class="form-select" id="state_search" name="state_search">
                        <option value="">Select State</option>
                    </select>
                    <label for="state_search">State Search</label>
                </div>
            </div>

            <div class="col">
                <div class="form-floating">
                    <input type="number" class="form-control" id="min_size_search" name="min_size_search">
                    <label for="min_size_search">Min Sq.Ft.</label>
                </div>
            </div>

            <div class="col">
                <div class="form-floating">
                    <input type="number" class="form-control" id="max_size_search" name="max_size_search">
                    <label for="max_size_search">Max Sq.Ft.</label>
                </div>
            </div>
            <div class="col">
                <input type="submit" class="btn btn-primary" value="Search">
            </div>
    </form>
</div>
<div class="map-view-container row flex-fill flex-column ">

    <div class="col flex-fill map-container">
        <div class="h-100">
            <div class="row">
                <div class="col-8">
                    <div class="map" id="map">Map</div>
                </div>
                <div class="col-4">
                    <div id="locations_container" class="locations-list">
                        <h1>Properties</h1>
                        <span id="results"></span>
                        <div id="locations_list">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<style>
    body {
        height: 100%;
    }

    .mvh-100 {
        min-height: 100vh;
    }

    .search-form {
        margin-bottom: 10px;
    }

    .locations-list {
        max-height: 100vh;
        overflow: scroll;
    }

    /* .map-container {
    } */

    .location {
        padding-top: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid black;
        display: none;
    }

    .location.show {
        display: flex;
    }

    #map {
        height: 400px;
        width: 100%;
        min-height: 100vh;
    }
</style>

<?php











get_footer();
