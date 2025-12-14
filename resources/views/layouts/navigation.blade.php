<div class="header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="float-left">
                    <div class="hamburger sidebar-toggle">
                        <span class="line"></span>
                        <span class="line"></span>
                        <span class="line"></span>
                    </div>
                </div>
                <div class="float-right">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" style="background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">
                                <i class="ti-power-off"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
                 <div class="search_wrap d-block d-sm-none d-md-none d-lg-none d-xl-none">
                 <div class="inbox-head top_search">
                        <form action="#" class=" position inbox_input">
                          <div class="input-append inner-append border_11">
                            <input type="text" class="sr-input" placeholder="Search Mail">
                            <button class="btn sr-btn append-btn btn33" type="button"><i class="fa fa-search"></i></button>
                          </div>
                        </form>
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>