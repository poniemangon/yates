@include('backend.layouts.head')

@include('backend.layouts.menu')

<div class="main-content property-abm">

    <div class="container-fluid">

        <div class="row">

            <div class="page-title col-md-6">

                <h4 class="section-title">Users <span class="total-section-title-content">Edit user {{ $userData->first_name }} {{ $userData->last_name }}</span></h4>

            </div>

            <div class="text-right col-md-6">

                <a href="{{ route('users-list') }}" class="btn btn-default btn-rounded">Back</a>

            </div> 

        </div>

        <div class="row">

            <div class="col-md-12">

                <div class="card maxed-content">

                    <div class="card-block">

                        <div class="row">

                            <div class="col-md-12 ml-auto mr-auto">

                                <form method="post" action="{{ route('edit-user', ['userId' => $userData->user_id]) }}" id="user-edition-form">

                                    <div class="spanish-content">

                                        <div class="row row-title-div mb-0">

                                            <div class="col-md-12">

                                                <h3 class="heading-title mb-0"><i>* Required information</i></h3>

                                            </div>

                                        </div>

                                        <div class="row row-title-div">

                                            <div class="col-md-12">

                                                <h3 class="heading-title">User information</h3>

                                            </div>

                                        </div>

                                        <div class="row">


                                            <div class="col-md-4 form-group">

                                                <label>Name *</label>

                                                <input type="text" name="first_name" class="form-control" value="{{ $userData->first_name }}">

                                            </div>

                                            <div class="col-md-4 form-group">

                                                <label>Surname *</label>

                                                <input type="text" name="last_name" class="form-control" value="{{ $userData->last_name }}">

                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-md-4 form-group">
                                                
                                                <label>Email *</label>

                                                <input type="text" name="email" class="form-control" value="{{ $userData->email }}">

                                            </div>

                                            <div class="col-md-4 form-group">

                                                <label>Password (optional)</label>

                                                <input type="password" name="password" class="form-control">

                                            </div>

                                            <div class="col-md-4 form-group">

                                                <label>Repeat password (optional)</label>

                                                <input type="password" name="repeatPassword" class="form-control">

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row mt-3">

                                        <div class="col-md-12 col-xs-12">

                                            <div class="text-right mrg-top-5">

                                                <a class="btn btn-custom btn-rounded mb-30" href="{{ route('users-list') }}">Cancel</a>

                                                <button type="submit" class="btn btn-custom btn-rounded mb-30 user-edition-button" data-saving-type="1">Save and keep editing</button>

                                                <button type="submit" class="btn btn-custom btn-rounded mb-30 user-edition-button" data-saving-type="2">Save</button>

                                            </div>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@include('backend.layouts.footer')

@include('backend.layouts.scripts')