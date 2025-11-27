
  <!--start header-->
  @include('admin.layouts.header')
  <!--end top header-->

  @include('admin.layouts.navbar')


   <!--start sidebar-->
   @include('admin.layouts.aside')
  <!--end sidebar-->
<div class="main-content app-content">
   <div class="container-fluid">
      <!-- Start::page-header -->
      <div
         class="my-4 d-md-flex d-block align-items-center justify-content-between page-header-breadcrumb"
         >
         <div class="page-leftheader">
            <h4 class="mb-0 page-title">Hi! Welcome Back</h4>
            <ol class="mb-0 breadcrumb">
               <li class="breadcrumb-item">
                  <a href="javascript:void(0);"
                     ><i
                     class="fe fe-home me-2 fs-14 d-inline-flex"
                     ></i
                     >Home</a
                     >
               </li>
               <li
                  class="breadcrumb-item active"
                  aria-current="page"
                  >
                  <a href="javascript:void(0);"
                     >Sales Dashboard</a
                     >
               </li>
            </ol>
         </div>
         <div class="page-rightheader">
            <div class="btn btn-list">
               <a
                  href="javascript:void(0);"
                  class="btn btn-info"
                  ><i
                  class="fe fe-settings me-1 d-inline-flex"
                  ></i>
               General Settings
               </a>
               <a
                  href="javascript:void(0);"
                  class="btn btn-danger"
                  ><i
                  class="fe fe-printer me-1 d-inline-flex"
                  ></i>
               Print
               </a>
               <a
                  href="javascript:void(0);"
                  class="btn btn-warning"
                  ><i
                  class="fe fe-shopping-cart me-1 d-inline-flex"
                  ></i>
               Buy Now
               </a>
            </div>
         </div>
      </div>
      <!-- End::page-header -->
      <!-- Row-1 -->
      <div class="row">
         <div class="col-xxl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="overflow-hidden card dash1-card">
               <div class="card-body">
                  <p class="mb-1">Total Sales</p>
                  <h2 class="mb-1 number-font">$3,257</h2>
                  <small class="fs-12 text-muted"
                     >Compared to Last Month</small
                     >
                  <span class="ratio bg-warning">76%</span>
                  <span class="ratio-text text-muted"
                     >Goals Reached</span
                     >
               </div>
               <div id="spark1" style="min-height: 60px">
                  <div
                     id="apexchartsb94fn6mg"
                     class="apexcharts-canvas apexchartsb94fn6mg apexcharts-theme-light"
                     style="width: 358px; height: 60px"
                     >
                     <svg
                        id="SvgjsSvg1885"
                        width="358"
                        height="60"
                        xmlns="http://www.w3.org/2000/svg"
                        version="1.1"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                        xmlns:svgjs="http://svgjs.dev"
                        class="apexcharts-svg"
                        xmlns:data="ApexChartsNS"
                        transform="translate(0, 0)"
                        style="background: transparent"
                        >
                      
                        
                        
                        <g
                           id="SvgjsG1887"
                           class="apexcharts-inner apexcharts-graphical"
                           transform="translate(0, 0)"
                           >
                           <defs id="SvgjsDefs1886">
                              <clipPath
                                 id="gridRectMaskb94fn6mg"
                                 >
                                 <rect
                                    id="SvgjsRect1891"
                                    width="363"
                                    height="61"
                                    x="-2.5"
                                    y="-0.5"
                                    rx="0"
                                    ry="0"
                                    opacity="1"
                                    stroke-width="0"
                                    stroke="none"
                                    stroke-dasharray="0"
                                    fill="#fff"
                                    ></rect>
                              </clipPath>
                              <clipPath
                                 id="forecastMaskb94fn6mg"
                                 ></clipPath>
                              <clipPath
                                 id="nonForecastMaskb94fn6mg"
                                 ></clipPath>
                              <clipPath
                                 id="gridRectMarkerMaskb94fn6mg"
                                 >
                                 <rect
                                    id="SvgjsRect1892"
                                    width="362"
                                    height="64"
                                    x="-2"
                                    y="-2"
                                    rx="0"
                                    ry="0"
                                    opacity="1"
                                    stroke-width="0"
                                    stroke="none"
                                    stroke-dasharray="0"
                                    fill="#fff"
                                    ></rect>
                              </clipPath>
                              <linearGradient
                                 id="SvgjsLinearGradient1897"
                                 x1="0"
                                 y1="0"
                                 x2="0"
                                 y2="1"
                                 >
                                 <stop
                                    id="SvgjsStop1898"
                                    stop-opacity="0.65"
                                    stop-color="rgba(250,5,122,0.65)"
                                    offset="0"
                                    ></stop>
                                 <stop
                                    id="SvgjsStop1899"
                                    stop-opacity="0.5"
                                    stop-color="rgba(253,130,189,0.5)"
                                    offset="1"
                                    ></stop>
                                 <stop
                                    id="SvgjsStop1900"
                                    stop-opacity="0.5"
                                    stop-color="rgba(253,130,189,0.5)"
                                    offset="1"
                                    ></stop>
                              </linearGradient>
                           </defs>
                           
                           <line
                              id="SvgjsLine1920"
                              x1="0"
                              y1="0"
                              x2="358"
                              y2="0"
                              stroke="#b6b6b6"
                              stroke-dasharray="0"
                              stroke-width="1"
                              stroke-linecap="butt"
                              class="apexcharts-ycrosshairs"
                              ></line>
                           <line
                              id="SvgjsLine1921"
                              x1="0"
                              y1="0"
                              x2="358"
                              y2="0"
                              stroke-dasharray="0"
                              stroke-width="0"
                              stroke-linecap="butt"
                              class="apexcharts-ycrosshairs-hidden"
                              ></line>
                           <g
                              id="SvgjsG1922"
                              class="apexcharts-xaxis"
                              transform="translate(0, 0)"
                              >
                              <g
                                 id="SvgjsG1923"
                                 class="apexcharts-xaxis-texts-g"
                                 transform="translate(0, -4)"
                                 ></g>
                           </g>
                           <g
                              id="SvgjsG1949"
                              class="apexcharts-yaxis-annotations"
                              ></g>
                           <g
                              id="SvgjsG1950"
                              class="apexcharts-xaxis-annotations"
                              ></g>
                           <g
                              id="SvgjsG1951"
                              class="apexcharts-point-annotations"
                              ></g>
                        </g>
                     </svg>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xxl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="overflow-hidden card dash1-card">
               <div class="card-body">
                  <p class="mb-1">Total User</p>
                  <h2 class="mb-1 number-font">1,678</h2>
                  <small class="fs-12 text-muted"
                     >Compared to Last Month</small
                     >
                  <span class="ratio bg-info">85%</span>
                  <span class="ratio-text text-muted"
                     >Goals Reached</span
                     >
               </div>
               <div id="spark2" style="min-height: 60px">
                  <div
                     id="apexchartsv6lu5xzci"
                     class="apexcharts-canvas apexchartsv6lu5xzci apexcharts-theme-light"
                     style="width: 358px; height: 60px"
                     >
                     <svg
                        id="SvgjsSvg1952"
                        width="358"
                        height="60"
                        xmlns="http://www.w3.org/2000/svg"
                        version="1.1"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                        xmlns:svgjs="http://svgjs.dev"
                        class="apexcharts-svg"
                        xmlns:data="ApexChartsNS"
                        transform="translate(0, 0)"
                        style="background: transparent"
                        >
                      
                        <rect
                           id="SvgjsRect1956"
                           width="0"
                           height="0"
                           x="0"
                           y="0"
                           rx="0"
                           ry="0"
                           opacity="1"
                           stroke-width="0"
                           stroke="none"
                           stroke-dasharray="0"
                           fill="#fefefe"
                           ></rect>
                        <g
                           id="SvgjsG2015"
                           class="apexcharts-yaxis"
                           rel="0"
                           transform="translate(-18, 0)"
                           ></g>
                        <g
                           id="SvgjsG1954"
                           class="apexcharts-inner apexcharts-graphical"
                           transform="translate(0, 0)"
                           >
                           <defs id="SvgjsDefs1953">
                              <clipPath
                                 id="gridRectMaskv6lu5xzci"
                                 >
                                 <rect
                                    id="SvgjsRect1958"
                                    width="363"
                                    height="61"
                                    x="-2.5"
                                    y="-0.5"
                                    rx="0"
                                    ry="0"
                                    opacity="1"
                                    stroke-width="0"
                                    stroke="none"
                                    stroke-dasharray="0"
                                    fill="#fff"
                                    ></rect>
                              </clipPath>
                              <clipPath
                                 id="forecastMaskv6lu5xzci"
                                 ></clipPath>
                              <clipPath
                                 id="nonForecastMaskv6lu5xzci"
                                 ></clipPath>
                              <clipPath
                                 id="gridRectMarkerMaskv6lu5xzci"
                                 >
                                 <rect
                                    id="SvgjsRect1959"
                                    width="362"
                                    height="64"
                                    x="-2"
                                    y="-2"
                                    rx="0"
                                    ry="0"
                                    opacity="1"
                                    stroke-width="0"
                                    stroke="none"
                                    stroke-dasharray="0"
                                    fill="#fff"
                                    ></rect>
                              </clipPath>
                              <linearGradient
                                 id="SvgjsLinearGradient1964"
                                 x1="0"
                                 y1="0"
                                 x2="0"
                                 y2="1"
                                 >
                                 <stop
                                    id="SvgjsStop1965"
                                    stop-opacity="0.65"
                                    stop-color="rgba(45,206,137,0.65)"
                                    offset="0"
                                    ></stop>
                                 <stop
                                    id="SvgjsStop1966"
                                    stop-opacity="0.5"
                                    stop-color="rgba(150,231,196,0.5)"
                                    offset="1"
                                    ></stop>
                                 <stop
                                    id="SvgjsStop1967"
                                    stop-opacity="0.5"
                                    stop-color="rgba(150,231,196,0.5)"
                                    offset="1"
                                    ></stop>
                              </linearGradient>
                           </defs>
                         
                           <line
                              id="SvgjsLine1987"
                              x1="0"
                              y1="0"
                              x2="358"
                              y2="0"
                              stroke="#b6b6b6"
                              stroke-dasharray="0"
                              stroke-width="1"
                              stroke-linecap="butt"
                              class="apexcharts-ycrosshairs"
                              ></line>
                           <line
                              id="SvgjsLine1988"
                              x1="0"
                              y1="0"
                              x2="358"
                              y2="0"
                              stroke-dasharray="0"
                              stroke-width="0"
                              stroke-linecap="butt"
                              class="apexcharts-ycrosshairs-hidden"
                              ></line>
                           <g
                              id="SvgjsG1989"
                              class="apexcharts-xaxis"
                              transform="translate(0, 0)"
                              >
                              <g
                                 id="SvgjsG1990"
                                 class="apexcharts-xaxis-texts-g"
                                 transform="translate(0, -4)"
                                 ></g>
                           </g>
                           <g
                              id="SvgjsG2016"
                              class="apexcharts-yaxis-annotations"
                              ></g>
                           <g
                              id="SvgjsG2017"
                              class="apexcharts-xaxis-annotations"
                              ></g>
                           <g
                              id="SvgjsG2018"
                              class="apexcharts-point-annotations"
                              ></g>
                        </g>
                     </svg>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xxl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="overflow-hidden card dash1-card">
               <div class="card-body">
                  <p class="mb-1">Total Income</p>
                  <h2 class="mb-1 number-font">$2,590</h2>
                  <small class="fs-12 text-muted"
                     >Compared to Last Month</small
                     >
                  <span class="ratio bg-danger">62%</span>
                  <span class="ratio-text text-muted"
                     >Goals Reached</span
                     >
               </div>
               <div id="spark3" style="min-height: 60px">
                  <div
                     id="apexchartsm2unfotik"
                     class="apexcharts-canvas apexchartsm2unfotik apexcharts-theme-light"
                     style="width: 358px; height: 60px"
                     >
                     <svg
                        id="SvgjsSvg2019"
                        width="358"
                        height="60"
                        xmlns="http://www.w3.org/2000/svg"
                        version="1.1"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                        xmlns:svgjs="http://svgjs.dev"
                        class="apexcharts-svg"
                        xmlns:data="ApexChartsNS"
                        transform="translate(0, 0)"
                        style="background: transparent"
                        >
                        
                        <rect
                           id="SvgjsRect2023"
                           width="0"
                           height="0"
                           x="0"
                           y="0"
                           rx="0"
                           ry="0"
                           opacity="1"
                           stroke-width="0"
                           stroke="none"
                           stroke-dasharray="0"
                           fill="#fefefe"
                           ></rect>
                        <g
                           id="SvgjsG2082"
                           class="apexcharts-yaxis"
                           rel="0"
                           transform="translate(-18, 0)"
                           ></g>
                        <g
                           id="SvgjsG2021"
                           class="apexcharts-inner apexcharts-graphical"
                           transform="translate(0, 0)"
                           >
                           <defs id="SvgjsDefs2020">
                              <clipPath
                                 id="gridRectMaskm2unfotik"
                                 >
                                 <rect
                                    id="SvgjsRect2025"
                                    width="363"
                                    height="61"
                                    x="-2.5"
                                    y="-0.5"
                                    rx="0"
                                    ry="0"
                                    opacity="1"
                                    stroke-width="0"
                                    stroke="none"
                                    stroke-dasharray="0"
                                    fill="#fff"
                                    ></rect>
                              </clipPath>
                              <clipPath
                                 id="forecastMaskm2unfotik"
                                 ></clipPath>
                              <clipPath
                                 id="nonForecastMaskm2unfotik"
                                 ></clipPath>
                              <clipPath
                                 id="gridRectMarkerMaskm2unfotik"
                                 >
                                 <rect
                                    id="SvgjsRect2026"
                                    width="362"
                                    height="64"
                                    x="-2"
                                    y="-2"
                                    rx="0"
                                    ry="0"
                                    opacity="1"
                                    stroke-width="0"
                                    stroke="none"
                                    stroke-dasharray="0"
                                    fill="#fff"
                                    ></rect>
                              </clipPath>
                              <linearGradient
                                 id="SvgjsLinearGradient2031"
                                 x1="0"
                                 y1="0"
                                 x2="0"
                                 y2="1"
                                 >
                                 <stop
                                    id="SvgjsStop2032"
                                    stop-opacity="0.65"
                                    stop-color="rgba(255,91,81,0.65)"
                                    offset="0"
                                    ></stop>
                                 <stop
                                    id="SvgjsStop2033"
                                    stop-opacity="0.5"
                                    stop-color="rgba(255,173,168,0.5)"
                                    offset="1"
                                    ></stop>
                                 <stop
                                    id="SvgjsStop2034"
                                    stop-opacity="0.5"
                                    stop-color="rgba(255,173,168,0.5)"
                                    offset="1"
                                    ></stop>
                              </linearGradient>
                           </defs>
                            
                           <line
                              id="SvgjsLine2054"
                              x1="0"
                              y1="0"
                              x2="358"
                              y2="0"
                              stroke="#b6b6b6"
                              stroke-dasharray="0"
                              stroke-width="1"
                              stroke-linecap="butt"
                              class="apexcharts-ycrosshairs"
                              ></line>
                           <line
                              id="SvgjsLine2055"
                              x1="0"
                              y1="0"
                              x2="358"
                              y2="0"
                              stroke-dasharray="0"
                              stroke-width="0"
                              stroke-linecap="butt"
                              class="apexcharts-ycrosshairs-hidden"
                              ></line>
                           <g
                              id="SvgjsG2056"
                              class="apexcharts-xaxis"
                              transform="translate(0, 0)"
                              >
                              <g
                                 id="SvgjsG2057"
                                 class="apexcharts-xaxis-texts-g"
                                 transform="translate(0, -4)"
                                 ></g>
                           </g>
                           <g
                              id="SvgjsG2083"
                              class="apexcharts-yaxis-annotations"
                              ></g>
                           <g
                              id="SvgjsG2084"
                              class="apexcharts-xaxis-annotations"
                              ></g>
                           <g
                              id="SvgjsG2085"
                              class="apexcharts-point-annotations"
                              ></g>
                        </g>
                     </svg>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xxl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="overflow-hidden card dash1-card">
               <div class="card-body">
                  <p class="mb-1">Total Tax</p>
                  <h2 class="mb-1 number-font">$1,954</h2>
                  <small class="fs-12 text-muted"
                     >Compared to Last Month</small
                     >
                  <span class="ratio bg-success">53%</span>
                  <span class="ratio-text text-muted"
                     >Goals Reached</span
                     >
               </div>
               <div id="spark4" style="min-height: 60px">
                  <div
                     id="apexchartsz13vx043"
                     class="apexcharts-canvas apexchartsz13vx043 apexcharts-theme-light"
                     style="width: 358px; height: 60px"
                     >
                     <svg
                        id="SvgjsSvg2086"
                        width="358"
                        height="60"
                        xmlns="http://www.w3.org/2000/svg"
                        version="1.1"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                        xmlns:svgjs="http://svgjs.dev"
                        class="apexcharts-svg"
                        xmlns:data="ApexChartsNS"
                        transform="translate(0, 0)"
                        style="background: transparent"
                        >
                  
                        <rect
                           id="SvgjsRect2090"
                           width="0"
                           height="0"
                           x="0"
                           y="0"
                           rx="0"
                           ry="0"
                           opacity="1"
                           stroke-width="0"
                           stroke="none"
                           stroke-dasharray="0"
                           fill="#fefefe"
                           ></rect>
                        <g
                           id="SvgjsG2149"
                           class="apexcharts-yaxis"
                           rel="0"
                           transform="translate(-18, 0)"
                           ></g>
                        <g
                           id="SvgjsG2088"
                           class="apexcharts-inner apexcharts-graphical"
                           transform="translate(0, 0)"
                           >
                           <defs id="SvgjsDefs2087">
                              <clipPath
                                 id="gridRectMaskz13vx043"
                                 >
                                 <rect
                                    id="SvgjsRect2092"
                                    width="363"
                                    height="61"
                                    x="-2.5"
                                    y="-0.5"
                                    rx="0"
                                    ry="0"
                                    opacity="1"
                                    stroke-width="0"
                                    stroke="none"
                                    stroke-dasharray="0"
                                    fill="#fff"
                                    ></rect>
                              </clipPath>
                              <clipPath
                                 id="forecastMaskz13vx043"
                                 ></clipPath>
                              <clipPath
                                 id="nonForecastMaskz13vx043"
                                 ></clipPath>
                              <clipPath
                                 id="gridRectMarkerMaskz13vx043"
                                 >
                                 <rect
                                    id="SvgjsRect2093"
                                    width="362"
                                    height="64"
                                    x="-2"
                                    y="-2"
                                    rx="0"
                                    ry="0"
                                    opacity="1"
                                    stroke-width="0"
                                    stroke="none"
                                    stroke-dasharray="0"
                                    fill="#fff"
                                    ></rect>
                              </clipPath>
                              <linearGradient
                                 id="SvgjsLinearGradient2098"
                                 x1="0"
                                 y1="0"
                                 x2="0"
                                 y2="1"
                                 >
                                 <stop
                                    id="SvgjsStop2099"
                                    stop-opacity="0.65"
                                    stop-color="rgba(252,191,9,0.65)"
                                    offset="0"
                                    ></stop>
                                 <stop
                                    id="SvgjsStop2100"
                                    stop-opacity="0.5"
                                    stop-color="rgba(254,223,132,0.5)"
                                    offset="1"
                                    ></stop>
                                 <stop
                                    id="SvgjsStop2101"
                                    stop-opacity="0.5"
                                    stop-color="rgba(254,223,132,0.5)"
                                    offset="1"
                                    ></stop>
                              </linearGradient>
                           </defs>
                         
                           <line
                              id="SvgjsLine2121"
                              x1="0"
                              y1="0"
                              x2="358"
                              y2="0"
                              stroke="#b6b6b6"
                              stroke-dasharray="0"
                              stroke-width="1"
                              stroke-linecap="butt"
                              class="apexcharts-ycrosshairs"
                              ></line>
                           <line
                              id="SvgjsLine2122"
                              x1="0"
                              y1="0"
                              x2="358"
                              y2="0"
                              stroke-dasharray="0"
                              stroke-width="0"
                              stroke-linecap="butt"
                              class="apexcharts-ycrosshairs-hidden"
                              ></line>
                           <g
                              id="SvgjsG2123"
                              class="apexcharts-xaxis"
                              transform="translate(0, 0)"
                              >
                              <g
                                 id="SvgjsG2124"
                                 class="apexcharts-xaxis-texts-g"
                                 transform="translate(0, -4)"
                                 ></g>
                           </g>
                           <g
                              id="SvgjsG2150"
                              class="apexcharts-yaxis-annotations"
                              ></g>
                           <g
                              id="SvgjsG2151"
                              class="apexcharts-xaxis-annotations"
                              ></g>
                           <g
                              id="SvgjsG2152"
                              class="apexcharts-point-annotations"
                              ></g>
                        </g>
                     </svg>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- End Row-1 -->
      <!-- Row-2 -->
      <div class="row">
         <div class="col-xl-8 col-lg-12 col-md-12">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-title">Sales Analysis</h5>
                  <div class="card-options">
                     <div class="p-0 btn-group">
                        <button
                           class="btn btn-outline-primary btn-sm active"
                           type="button"
                           >
                        Week
                        </button>
                        <button
                           class="btn btn-outline-primary btn-sm"
                           type="button"
                           >
                        Month
                        </button>
                        <button
                           class="btn btn-outline-primary btn-sm"
                           type="button"
                           >
                        Year
                        </button>
                     </div>
                  </div>
               </div>
               <div class="card-body">
                  <div class="mb-3 row">
                     <div class="col-xl-3 col-6">
                        <p class="mb-1">Total Sales</p>
                        <h3 class="mb-0 number-font1">
                           $52,618
                        </h3>
                        <p class="fs-12 text-muted">
                           <span class="text-danger me-1"
                              ><i
                              class="fe fe-arrow-down"
                              ></i
                              >0.9%</span
                              >this month
                        </p>
                     </div>
                     <div class="col-xl-3 col-6">
                        <p class="mb-1">Maximum Sales</p>
                        <h3 class="mb-0 number-font1">
                           $26,197
                        </h3>
                        <p class="fs-12 text-muted">
                           <span class="text-success me-1"
                              ><i
                              class="fe fe-arrow-up"
                              ></i
                              >0.5%</span
                              >this month
                        </p>
                     </div>
                     <div class="col-xl-3 col-6">
                        <p class="mb-1">Total Units Sold</p>
                        <h3 class="mb-0 number-font1">
                           13,876
                        </h3>
                        <p class="fs-12 text-muted">
                           <span class="text-danger me-1"
                              ><i
                              class="fe fe-arrow-down"
                              ></i
                              >0.8%</span
                              >this month
                        </p>
                     </div>
                     <div class="col-xl-3 col-6">
                        <p class="mb-1">
                           Maximum Units Sold
                        </p>
                        <h3 class="mb-0 number-font1">
                           5,876
                        </h3>
                        <p class="fs-12 text-muted">
                           <span class="text-success me-1"
                              ><i
                              class="fe fe-arrow-up"
                              ></i
                              >0.6%</span
                              >this month
                        </p>
                     </div>
                  </div>
                  <div
                     id="sales-analysis"
                     class="text-center chart-tasks chart-dropshadow"
                     _echarts_instance_="ec_1763976603212"
                     style="
                     user-select: none;
                     -webkit-tap-highlight-color: rgba(
                     0,
                     0,
                     0,
                     0
                     );
                     position: relative;
                     "
                     >
                     <div
                        style="
                        position: relative;
                        width: 934px;
                        height: 240px;
                        padding: 0px;
                        margin: 0px;
                        border-width: 0px;
                        cursor: pointer;
                        "
                        >
                        <canvas
                           data-zr-dom-id="zr_0"
                           width="1167"
                           height="300"
                           style="
                           position: absolute;
                           left: 0px;
                           top: 0px;
                           width: 934px;
                           height: 240px;
                           user-select: none;
                           -webkit-tap-highlight-color: rgba(
                           0,
                           0,
                           0,
                           0
                           );
                           padding: 0px;
                           margin: 0px;
                           border-width: 0px;
                           "
                           ></canvas>
                     </div>
                     <div
                        class=""
                        style="
                        position: absolute;
                        display: block;
                        border-style: solid;
                        white-space: nowrap;
                        z-index: 9999999;
                        will-change: transform;
                        box-shadow: rgba(0, 0, 0, 0.2)
                        1px 2px 10px;
                        transition: opacity 0.2s
                        cubic-bezier(
                        0.23,
                        1,
                        0.32,
                        1
                        ),
                        visibility 0.2s
                        cubic-bezier(
                        0.23,
                        1,
                        0.32,
                        1
                        ),
                        transform 0.4s
                        cubic-bezier(
                        0.23,
                        1,
                        0.32,
                        1
                        );
                        background-color: rgba(
                        50,
                        50,
                        50,
                        0.7
                        );
                        border-width: 0px;
                        border-radius: 4px;
                        color: rgb(255, 255, 255);
                        font: 14px / 21px
                        'Microsoft YaHei';
                        padding: 10px;
                        top: 0px;
                        left: 0px;
                        transform: translate3d(
                        300px,
                        152px,
                        0px
                        );
                        border-color: rgb(
                        255,
                        255,
                        255
                        );
                        pointer-events: none;
                        "
                        >
                        <div
                           style="
                           margin: 0px 0 0;
                           line-height: 1;
                           "
                           >
                           <div
                              style="
                              margin: 0px 0 0;
                              line-height: 1;
                              "
                              >
                              <div
                                 style="
                                 font-size: 14px;
                                 color: #ffffff;
                                 font-weight: 400;
                                 line-height: 1;
                                 "
                                 >
                                 Apr
                              </div>
                              <div
                                 style="
                                 margin: 10px 0 0;
                                 line-height: 1;
                                 "
                                 >
                                 <div
                                    style="
                                    margin: 0px 0 0;
                                    line-height: 1;
                                    "
                                    >
                                    <div
                                       style="
                                       margin: 0px
                                       0 0;
                                       line-height: 1;
                                       "
                                       >
                                       <span
                                          style="
                                          display: inline-block;
                                          margin-right: 4px;
                                          border-radius: 10px;
                                          width: 10px;
                                          height: 10px;
                                          background-color: #fd6f82;
                                          "
                                          ></span
                                          ><span
                                          style="
                                          font-size: 14px;
                                          color: #ffffff;
                                          font-weight: 400;
                                          margin-left: 2px;
                                          "
                                          >Total Units
                                       Sold</span
                                          ><span
                                          style="
                                          float: right;
                                          margin-left: 20px;
                                          font-size: 14px;
                                          color: #ffffff;
                                          font-weight: 900;
                                          "
                                          >10</span
                                          >
                                       <div
                                          style="
                                          clear: both;
                                          "
                                          ></div>
                                    </div>
                                    <div
                                       style="
                                       clear: both;
                                       "
                                       ></div>
                                 </div>
                                 <div
                                    style="
                                    margin: 10px 0 0;
                                    line-height: 1;
                                    "
                                    >
                                    <div
                                       style="
                                       margin: 0px
                                       0 0;
                                       line-height: 1;
                                       "
                                       >
                                       <span
                                          style="
                                          display: inline-block;
                                          margin-right: 4px;
                                          border-radius: 10px;
                                          width: 10px;
                                          height: 10px;
                                          background-color: rgb(
                                          112,
                                          94,
                                          200
                                          );
                                          "
                                          ></span
                                          ><span
                                          style="
                                          font-size: 14px;
                                          color: #ffffff;
                                          font-weight: 400;
                                          margin-left: 2px;
                                          "
                                          >Total
                                       Sales</span
                                          ><span
                                          style="
                                          float: right;
                                          margin-left: 20px;
                                          font-size: 14px;
                                          color: #ffffff;
                                          font-weight: 900;
                                          "
                                          >22</span
                                          >
                                       <div
                                          style="
                                          clear: both;
                                          "
                                          ></div>
                                    </div>
                                    <div
                                       style="
                                       clear: both;
                                       "
                                       ></div>
                                 </div>
                                 <div
                                    style="clear: both"
                                    ></div>
                              </div>
                              <div
                                 style="clear: both"
                                 ></div>
                           </div>
                           <div style="clear: both"></div>
                        </div>
                     </div>
                  </div>
                  <div class="mt-2 text-center">
                     <span class="me-4"
                        ><span
                        class="dot-label bg-primary"
                        ></span
                        >Total Sales</span
                        >
                     <span
                        ><span
                        class="dot-label bg-secondary"
                        ></span
                        >Total Units Sold</span
                        >
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xl-4 col-lg-12 col-md-12">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-title">Recent Activity</h5>
                  <div class="card-options">
                     <a
                        href="javascript:void(0);"
                        class="option-dots"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                        ><i
                        class="fe fe-more-horizontal"
                        ></i
                        ></a>
                     <div
                        class="dropdown-menu dropdown-menu-right"
                        >
                        <a
                           class="dropdown-item"
                           href="javascript:void(0);"
                           >Today</a
                           >
                        <a
                           class="dropdown-item"
                           href="javascript:void(0);"
                           >Last Week</a
                           >
                        <a
                           class="dropdown-item"
                           href="javascript:void(0);"
                           >Last Month</a
                           >
                        <a
                           class="dropdown-item"
                           href="javascript:void(0);"
                           >Last Year</a
                           >
                     </div>
                  </div>
               </div>
               <div class="card-body pe-0">
                  <div
                     class="latest-timeline scrollbar3"
                     id="scrollbar3"
                     data-simplebar="init"
                     >
                     <div
                        class="simplebar-wrapper"
                        style="margin: 0px"
                        >
                        <div
                           class="simplebar-height-auto-observer-wrapper"
                           >
                           <div
                              class="simplebar-height-auto-observer"
                              ></div>
                        </div>
                        <div class="simplebar-mask">
                           <div
                              class="simplebar-offset"
                              style="
                              right: 0px;
                              bottom: 0px;
                              "
                              >
                              <div
                                 class="simplebar-content-wrapper"
                                 tabindex="0"
                                 role="region"
                                 aria-label="scrollable content"
                                 style="
                                 height: 100%;
                                 overflow: hidden
                                 scroll;
                                 "
                                 >
                                 <div
                                    class="simplebar-content"
                                    style="padding: 0px"
                                    >
                                    <ul
                                       class="mb-0 timeline pe-4"
                                       >
                                       <li
                                          class="mt-0"
                                          >
                                          <div
                                             class="d-flex"
                                             >
                                             <span
                                                class="time-data"
                                                >Task
                                             Finished</span
                                                ><span
                                                class="ms-auto text-muted fs-11"
                                                >09
                                             June
                                             2020</span
                                                >
                                          </div>
                                          <p
                                             class="text-muted fs-12"
                                             >
                                             <span
                                                class="text-info"
                                                >Joseph
                                             Ellison</span
                                                >
                                             finished
                                             task
                                             on<a
                                                href="javascript:void(0);"
                                                class="fw-semibold"
                                                >
                                             Project
                                             Management</a
                                                >
                                          </p>
                                       </li>
                                       <li>
                                          <div
                                             class="d-flex"
                                             >
                                             <span
                                                class="time-data"
                                                >New
                                             Comment</span
                                                ><span
                                                class="ms-auto text-muted fs-11"
                                                >05
                                             June
                                             2020</span
                                                >
                                          </div>
                                          <p
                                             class="text-muted fs-12"
                                             >
                                             <span
                                                class="text-info"
                                                >Elizabeth
                                             Scott</span
                                                >
                                             Product
                                             delivered<a
                                                href="javascript:void(0);"
                                                class="fw-semibold"
                                                >
                                             Service
                                             Management</a
                                                >
                                          </p>
                                       </li>
                                       <li>
                                          <div
                                             class="d-flex"
                                             >
                                             <span
                                                class="time-data"
                                                >Target
                                             Completed</span
                                                ><span
                                                class="ms-auto text-muted fs-11"
                                                >01
                                             June
                                             2020</span
                                                >
                                          </div>
                                          <p
                                             class="text-muted fs-12"
                                             >
                                             <span
                                                class="text-info"
                                                >Sonia
                                             Peters</span
                                                >
                                             finished
                                             target
                                             on<a
                                                href="javascript:void(0);"
                                                class="fw-semibold"
                                                >
                                             this
                                             month
                                             Sales</a
                                                >
                                          </p>
                                       </li>
                                       <li>
                                          <div
                                             class="d-flex"
                                             >
                                             <span
                                                class="time-data"
                                                >Revenue
                                             Sources</span
                                                ><span
                                                class="ms-auto text-muted fs-11"
                                                >26
                                             May
                                             2020</span
                                                >
                                          </div>
                                          <p
                                             class="text-muted fs-12"
                                             >
                                             <span
                                                class="text-info"
                                                >Justin
                                             Nash</span
                                                >
                                             source
                                             report
                                             on<a
                                                href="javascript:void(0);"
                                                class="fw-semibold"
                                                >
                                             Generated</a
                                                >
                                          </p>
                                       </li>
                                       <li>
                                          <div
                                             class="d-flex"
                                             >
                                             <span
                                                class="time-data"
                                                >Dispatched
                                             Order</span
                                                ><span
                                                class="ms-auto text-muted fs-11"
                                                >22
                                             May
                                             2020</span
                                                >
                                          </div>
                                          <p
                                             class="text-muted fs-12"
                                             >
                                             <span
                                                class="text-info"
                                                >Ella
                                             Lambert</span
                                                >
                                             ontime
                                             order
                                             delivery
                                             <a
                                                href="javascript:void(0);"
                                                class="fw-semibold"
                                                >Service
                                             Management</a
                                                >
                                          </p>
                                       </li>
                                       <li>
                                          <div
                                             class="d-flex"
                                             >
                                             <span
                                                class="time-data"
                                                >New
                                             User
                                             Added</span
                                                ><span
                                                class="ms-auto text-muted fs-11"
                                                >19
                                             May
                                             2020</span
                                                >
                                          </div>
                                          <p
                                             class="text-muted fs-12"
                                             >
                                             <span
                                                class="text-info"
                                                >Nicola
                                             Blake</span
                                                >
                                             visit
                                             the
                                             site<a
                                                href="javascript:void(0);"
                                                class="fw-semibold"
                                                >
                                             Membership
                                             allocated</a
                                                >
                                          </p>
                                       </li>
                                       <li>
                                          <div
                                             class="d-flex"
                                             >
                                             <span
                                                class="time-data"
                                                >Revenue
                                             Sources</span
                                                ><span
                                                class="ms-auto text-muted fs-11"
                                                >15
                                             May
                                             2020</span
                                                >
                                          </div>
                                          <p
                                             class="text-muted fs-12"
                                             >
                                             <span
                                                class="text-info"
                                                >Richard
                                             Mills</span
                                                >
                                             source
                                             report
                                             on<a
                                                href="javascript:void(0);"
                                                class="fw-semibold"
                                                >
                                             Generated</a
                                                >
                                          </p>
                                       </li>
                                       <li
                                          class="mb-0"
                                          >
                                          <div
                                             class="d-flex"
                                             >
                                             <span
                                                class="time-data"
                                                >New
                                             Order
                                             Placed</span
                                                ><span
                                                class="ms-auto text-muted fs-11"
                                                >11
                                             May
                                             2020</span
                                                >
                                          </div>
                                          <p
                                             class="text-muted fs-12"
                                             >
                                             <span
                                                class="text-info"
                                                >Steven
                                             Hart</span
                                                >
                                             is
                                             proces
                                             the
                                             order<a
                                                href="javascript:void(0);"
                                                class="fw-semibold"
                                                >
                                             #987</a
                                                >
                                          </p>
                                       </li>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div
                           class="simplebar-placeholder"
                           style="
                           width: auto;
                           height: 468px;
                           "
                           ></div>
                     </div>
                     <div
                        class="simplebar-track simplebar-horizontal"
                        style="visibility: hidden"
                        >
                        <div
                           class="simplebar-scrollbar"
                           style="
                           width: 0px;
                           display: none;
                           "
                           ></div>
                     </div>
                     <div
                        class="simplebar-track simplebar-vertical"
                        style="visibility: visible"
                        >
                        <div
                           class="simplebar-scrollbar"
                           style="
                           height: 289px;
                           transform: translate3d(
                           0px,
                           79px,
                           0px
                           );
                           display: block;
                           "
                           ></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- End Row-2 -->
      <!-- Row-3 -->
      <div class="row">
         <div class="col-xxl-3 col-xl-6 col-sm-12">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-title">Recent Customers</h5>
                  <div class="card-options">
                     <a
                        href="javascript:void(0);"
                        class="option-dots"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                        ><i
                        class="fe fe-more-horizontal"
                        ></i
                        ></a>
                     <div
                        class="dropdown-menu dropdown-menu-right"
                        >
                        <a
                           class="dropdown-item"
                           href="javascript:void(0);"
                           >Today</a
                           >
                        <a
                           class="dropdown-item"
                           href="javascript:void(0);"
                           >Last Week</a
                           >
                        <a
                           class="dropdown-item"
                           href="javascript:void(0);"
                           >Last Month</a
                           >
                        <a
                           class="dropdown-item"
                           href="javascript:void(0);"
                           >Last Year</a
                           >
                     </div>
                  </div>
               </div>
               <div class="card-body">
                  <div class="list-card">
                     <span
                        class="bg-warning list-bar"
                        ></span>
                     <div class="row align-items-center">
                        <div class="col-9 col-sm-9">
                           <div class="mt-0 d-sm-flex">
                              <span
                                 class="avatar avatar-rounded"
                                 >
                              <img
                                 src="../assets/images/faces/1.jpg"
                                 alt="img"
                                 />
                              </span>
                              <div
                                 class="media-body ms-3"
                                 >
                                 <div
                                    class="mt-1 d-md-flex align-items-center"
                                    >
                                    <h6 class="mb-1">
                                       Lisa Marshall
                                    </h6>
                                 </div>
                                 <span
                                    class="mb-0 fs-13 text-muted"
                                    >User ID:#2342<span
                                    class="ms-2 text-success fs-13 fw-semibold d-inline-flex"
                                    >Paid</span
                                    ></span
                                    >
                              </div>
                           </div>
                        </div>
                        <div class="col-3 col-sm-3">
                           <div class="text-end">
                              <span
                                 class="fw-semibold fs-18 number-font"
                                 >$558</span
                                 >
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="list-card">
                     <span class="bg-danger list-bar"></span>
                     <div class="row align-items-center">
                        <div class="col-9 col-sm-9">
                           <div class="mt-0 d-sm-flex">
                              <span
                                 class="avatar avatar-rounded"
                                 >
                              <img
                                 src="../assets/images/faces/2.jpg"
                                 alt="img"
                                 />
                              </span>
                              <div
                                 class="media-body ms-3"
                                 >
                                 <div
                                    class="mt-1 d-md-flex align-items-center"
                                    >
                                    <h6 class="mb-1">
                                       Sonia Smith
                                    </h6>
                                 </div>
                                 <span
                                    class="mb-0 fs-13 text-muted"
                                    >User ID:#8763<span
                                    class="ms-2 text-success fs-13 fw-semibold d-inline-flex"
                                    >Paid</span
                                    ></span
                                    >
                              </div>
                           </div>
                        </div>
                        <div class="col-3 col-sm-3">
                           <div class="text-end">
                              <span
                                 class="fw-semibold fs-18 number-font"
                                 >$358</span
                                 >
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="list-card">
                     <span
                        class="bg-success list-bar"
                        ></span>
                     <div class="row align-items-center">
                        <div class="col-9 col-sm-9">
                           <div class="mt-0 d-sm-flex">
                              <span
                                 class="avatar avatar-rounded"
                                 >
                              <img
                                 src="../assets/images/faces/11.jpg"
                                 alt="img"
                                 />
                              </span>
                              <div
                                 class="media-body ms-3"
                                 >
                                 <div
                                    class="mt-1 d-md-flex align-items-center"
                                    >
                                    <h6 class="mb-1">
                                       Joseph Abraham
                                    </h6>
                                 </div>
                                 <span
                                    class="mb-0 fs-13 text-muted"
                                    >User ID:#1076<span
                                    class="ms-2 text-danger fs-13 fw-semibold d-inline-flex"
                                    >Pending</span
                                    ></span
                                    >
                              </div>
                           </div>
                        </div>
                        <div class="col-3 col-sm-3">
                           <div class="text-end">
                              <span
                                 class="fw-semibold fs-18 number-font"
                                 >$796</span
                                 >
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="list-card">
                     <span
                        class="bg-primary list-bar"
                        ></span>
                     <div class="row align-items-center">
                        <div class="col-9 col-sm-9">
                           <div class="mt-0 d-sm-flex">
                              <span
                                 class="avatar avatar-rounded"
                                 >
                              <img
                                 src="../assets/images/faces/3.jpg"
                                 alt="img"
                                 />
                              </span>
                              <div
                                 class="media-body ms-3"
                                 >
                                 <div
                                    class="mt-1 d-md-flex align-items-center"
                                    >
                                    <h6 class="mb-1">
                                       Joseph Abraham
                                    </h6>
                                 </div>
                                 <span
                                    class="mb-0 fs-13 text-muted"
                                    >User ID:#986<span
                                    class="ms-2 text-success fs-13 fw-semibold d-inline-flex"
                                    >Paid</span
                                    ></span
                                    >
                              </div>
                           </div>
                        </div>
                        <div class="col-3 col-sm-3">
                           <div class="text-end">
                              <span
                                 class="fw-semibold fs-18 number-font"
                                 >$867</span
                                 >
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xxl-3 col-xl-6 col-sm-12">
            <div class="card">
               <div class="card-body">
                  <div
                     class="d-flex align-items-end justify-content-between"
                     >
                     <div>
                        <p class="mb-1 fs-14">Users</p>
                        <h2 class="mb-0">
                           <span class="number-font1"
                              >12,769</span
                              >
                        </h2>
                     </div>
                     <span
                        class="avatar avatar-lg avatar-rounded bg-success-transparent"
                        >
                     <i class="las la-users fs-3"></i>
                     </span>
                  </div>
                  <div class="mt-2 d-flex">
                     <div>
                        <span class="text-muted fs-12 me-1"
                           >This Month</span
                           >
                        <span class="number-font fs-12"
                           ><i
                           class="ri-arrow-up-s-fill me-1 text-success"
                           ></i
                           >10,876</span
                           >
                     </div>
                     <div class="ms-auto">
                        <span class="text-muted fs-12 me-1"
                           >Last Month</span
                           >
                        <span class="number-font fs-12"
                           ><i
                           class="ri-arrow-down-s-fill me-1 text-danger"
                           ></i
                           >8,610</span
                           >
                     </div>
                  </div>
               </div>
            </div>
            <div class="card">
               <div class="card-body">
                  <div
                     class="d-flex align-items-end justify-content-between"
                     >
                     <div>
                        <p class="mb-1 fs-14">Sales</p>
                        <h2 class="mb-0">
                           <span class="number-font1"
                              >$34,789</span
                              >
                        </h2>
                     </div>
                     <span
                        class="avatar avatar-lg avatar-rounded bg-warning-transparent"
                        >
                     <i
                        class="las la-hand-holding-usd fs-3"
                        ></i>
                     </span>
                  </div>
                  <div class="mt-2 d-flex">
                     <div>
                        <span class="text-muted fs-12 me-1"
                           >This Month</span
                           >
                        <span class="number-font fs-12"
                           ><i
                           class="ri-arrow-up-s-fill me-1 text-success"
                           ></i
                           >$12,786</span
                           >
                     </div>
                     <div class="ms-auto">
                        <span class="text-muted fs-12 me-1"
                           >Last Month</span
                           >
                        <span class="number-font fs-12"
                           ><i
                           class="ri-arrow-down-s-fill me-1 text-danger"
                           ></i
                           >$89,987</span
                           >
                     </div>
                  </div>
               </div>
            </div>
            <div class="card">
               <div class="card-body">
                  <div
                     class="d-flex align-items-end justify-content-between"
                     >
                     <div>
                        <p class="mb-1 fs-14">
                           Subscribers
                        </p>
                        <h2 class="mb-0">
                           <span class="number-font1"
                              >4,876</span
                              >
                        </h2>
                     </div>
                     <span
                        class="avatar avatar-lg avatar-rounded bg-info-transparent"
                        >
                     <i
                        class="las la-thumbs-up fs-3"
                        ></i>
                     </span>
                  </div>
                  <div class="mt-2 d-flex">
                     <div>
                        <span class="text-muted fs-12 me-1"
                           >This Month</span
                           >
                        <span class="number-font fs-12"
                           ><i
                           class="ri-arrow-up-s-fill me-1 text-success"
                           ></i
                           >1,034</span
                           >
                     </div>
                     <div class="ms-auto">
                        <span class="text-muted fs-12 me-1"
                           >Last Month</span
                           >
                        <span class="number-font fs-12"
                           ><i
                           class="ri-arrow-down-s-fill me-1 text-danger"
                           ></i
                           >88,345</span
                           >
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xxl-3 col-xl-6 col-sm-12">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-title">
                     Revenue in Countries
                  </h5>
                  <div class="card-options">
                     <a
                        href="javascript:void(0);"
                        class="option-dots"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                        ><i
                        class="fe fe-more-horizontal"
                        ></i
                        ></a>
                     <div
                        class="dropdown-menu dropdown-menu-right"
                        >
                        <a
                           class="dropdown-item"
                           href="javascript:void(0);"
                           >Today</a
                           >
                        <a
                           class="dropdown-item"
                           href="javascript:void(0);"
                           >Last Week</a
                           >
                        <a
                           class="dropdown-item"
                           href="javascript:void(0);"
                           >Last Month</a
                           >
                        <a
                           class="dropdown-item"
                           href="javascript:void(0);"
                           >Last Year</a
                           >
                     </div>
                  </div>
               </div>
               <div class="card-body">
                  <div class="">
                     <div class="mb-4">
                        <div class="d-flex">
                           <span class=""
                              ><img
                              src="../assets/images/flags/us_flag.jpg"
                              class="revenue-country-flags me-2"
                              alt=""
                              />United States</span
                              >
                           <div class="ms-auto">
                              <span
                                 class="mx-1 text-success"
                                 ><i
                                 class="fe fe-trending-up"
                                 ></i></span
                                 ><span class="number-font"
                                 >$45,870</span
                                 >
                           </div>
                        </div>
                        <div
                           class="mt-1 progress"
                           role="progressbar"
                           aria-label="Example 6px high"
                           style="height: 6px"
                           >
                           <div
                              class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                              style="width: 80%"
                              ></div>
                        </div>
                     </div>
                     <div class="mb-4">
                        <div class="d-flex">
                           <span class=""
                              ><img
                              src="../assets/images/flags/russia_flag.jpg"
                              class="revenue-country-flags me-2"
                              alt=""
                              />Russia</span
                              >
                           <div class="ms-auto">
                              <span
                                 class="mx-1 text-success"
                                 ><i
                                 class="fe fe-trending-up"
                                 ></i></span
                                 ><span class="number-font"
                                 >$22,710</span
                                 >
                           </div>
                        </div>
                        <div
                           class="mt-2 progress"
                           role="progressbar"
                           aria-label="Example 6px high"
                           style="height: 6px"
                           >
                           <div
                              class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                              style="width: 50%"
                              ></div>
                        </div>
                     </div>
                     <div class="mb-4">
                        <div class="d-flex">
                           <span class=""
                              ><img
                              src="../assets/images/flags/canada_flag.jpg"
                              class="revenue-country-flags me-2"
                              alt=""
                              />Canada</span
                              >
                           <div class="ms-auto">
                              <span
                                 class="mx-1 text-danger"
                                 ><i
                                 class="fe fe-trending-down"
                                 ></i></span
                                 ><span class="number-font"
                                 >$56,291</span
                                 >
                           </div>
                        </div>
                        <div
                           class="mt-2 progress"
                           role="progressbar"
                           aria-label="Example 6px high"
                           style="height: 6px"
                           >
                           <div
                              class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                              style="width: 80%"
                              ></div>
                        </div>
                     </div>
                     <div class="mb-4">
                        <div class="d-flex">
                           <span class=""
                              ><img
                              src="../assets/images/flags/germany_flag.jpg"
                              class="revenue-country-flags me-2"
                              alt=""
                              />Germany</span
                              >
                           <div class="ms-auto">
                              <span
                                 class="mx-1 text-success"
                                 ><i
                                 class="fe fe-trending-up"
                                 ></i></span
                                 ><span class="number-font"
                                 >$67,357</span
                                 >
                           </div>
                        </div>
                        <div
                           class="mt-2 progress"
                           role="progressbar"
                           aria-label="Example 6px high"
                           style="height: 6px"
                           >
                           <div
                              class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                              style="width: 70%"
                              ></div>
                        </div>
                     </div>
                     <div class="mb-4">
                        <div class="d-flex">
                           <span class=""
                              ><img
                              src="../assets/images/flags/china_flag.jpg"
                              class="revenue-country-flags me-2"
                              alt=""
                              />China</span
                              >
                           <div class="ms-auto">
                              <span
                                 class="mx-1 text-success"
                                 ><i
                                 class="fe fe-trending-up"
                                 ></i></span
                                 ><span class="number-font"
                                 >$34,209</span
                                 >
                           </div>
                        </div>
                        <div
                           class="mt-2 progress"
                           role="progressbar"
                           aria-label="Example 6px high"
                           style="height: 6px"
                           >
                           <div
                              class="progress-bar progress-bar-striped progress-bar-animated bg-indigo"
                              style="width: 60%"
                              ></div>
                        </div>
                     </div>
                     <div class="mb-0">
                        <div class="d-flex">
                           <span class=""
                              ><img
                              src="../assets/images/flags/uae_flag.jpg"
                              class="revenue-country-flags me-2"
                              alt=""
                              />Uae</span
                              >
                           <div class="ms-auto">
                              <span
                                 class="mx-1 text-success"
                                 ><i
                                 class="fe fe-trending-up"
                                 ></i></span
                                 ><span class="number-font"
                                 >$12,876</span
                                 >
                           </div>
                        </div>
                        <div
                           class="mt-2 progress"
                           role="progressbar"
                           aria-label="Example 6px high"
                           style="height: 6px"
                           >
                           <div
                              class="progress-bar progress-bar-striped progress-bar-animated bg-warning"
                              style="width: 40%"
                              ></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xxl-3 col-xl-6 col-sm-12">
            <div class="overflow-hidden card">
               <div class="card-header border-bottom">
                  <h4 class="card-title fw-semibold">
                     Latest Transactions
                  </h4>
               </div>
               <div class="p-0 mt-1 card-body customers">
                  <ul
                     class="py-1 latest-transactions-list list-unstyled"
                     >
                     <li>
                        <a
                           href="javascript:void(0);"
                           class="stretched-link"
                           ></a>
                        <div
                           class="d-flex align-items-center"
                           >
                           <div class="me-3">
                              <span
                                 class="avatar avatar-rounded bg-primary-transparent"
                                 >
                              <i
                                 class="fe fe-chevrons-right"
                                 ></i>
                              </span>
                           </div>
                           <div
                              class="d-flex align-items-center justify-content-between flex-fill"
                              >
                              <div class="mt-0">
                                 <h5
                                    class="mb-1 fs-13 fw-normal text-dark"
                                    >
                                    To Bel Bcron
                                    Bank<span
                                       class="fs-13 fw-semibold ms-1"
                                       >Savings
                                    Section</span
                                       >
                                 </h5>
                                 <p
                                    class="mb-0 fs-12 text-muted"
                                    >
                                    Transfer 4.53pm
                                 </p>
                              </div>
                              <span
                                 class="ms-auto fs-14 fw-semibold"
                                 >
                              <span class="float-end"
                                 >-$2,543</span
                                 >
                              </span>
                           </div>
                        </div>
                     </li>
                     <li>
                        <a
                           href="javascript:void(0);"
                           class="stretched-link"
                           ></a>
                        <div
                           class="d-flex align-items-center"
                           >
                           <div class="me-3">
                              <span
                                 class="avatar avatar-rounded bg-warning-transparent"
                                 >
                              <i
                                 class="fe fe-briefcase"
                                 ></i>
                              </span>
                           </div>
                           <div
                              class="d-flex align-items-center justify-content-between flex-fill"
                              >
                              <div class="mt-0">
                                 <h5
                                    class="mb-1 fs-13 fw-normal text-dark"
                                    >
                                    Payment For
                                    <span
                                       class="fs-13 fw-semibold ms-1"
                                       >Day Job</span
                                       >
                                 </h5>
                                 <p
                                    class="mb-0 fs-12 text-muted"
                                    >
                                    Received 2.45pm
                                 </p>
                              </div>
                              <span
                                 class="ms-auto fs-14 fw-semibold"
                                 >
                              <span class="float-end"
                                 >+$32,543</span
                                 >
                              </span>
                           </div>
                        </div>
                     </li>
                     <li>
                        <a
                           href="javascript:void(0);"
                           class="stretched-link"
                           ></a>
                        <div
                           class="d-flex align-items-center"
                           >
                           <div class="me-3">
                              <span
                                 class="avatar avatar-rounded bg-success-transparent"
                                 >
                              <i
                                 class="fe fe-dollar-sign"
                                 ></i>
                              </span>
                           </div>
                           <div
                              class="d-flex align-items-center justify-content-between flex-fill"
                              >
                              <div class="mt-0">
                                 <h5
                                    class="mb-1 fs-13 fw-normal text-dark"
                                    >
                                    Bought items
                                    from<span
                                       class="fs-13 fw-semibold ms-1"
                                       >Ecommerce
                                    site</span
                                       >
                                 </h5>
                                 <p
                                    class="mb-0 fs-12 text-muted"
                                    >
                                    Payment 8.00am
                                 </p>
                              </div>
                              <span
                                 class="ms-auto fs-14 fw-semibold"
                                 >
                              <span class="float-end"
                                 >-$256</span
                                 >
                              </span>
                           </div>
                        </div>
                     </li>
                     <li>
                        <a
                           href="javascript:void(0);"
                           class="stretched-link"
                           ></a>
                        <div
                           class="d-flex align-items-center"
                           >
                           <div class="me-3">
                              <span
                                 class="avatar avatar-rounded bg-info-transparent"
                                 >
                              <i
                                 class="fe fe-file-text"
                                 ></i>
                              </span>
                           </div>
                           <div
                              class="d-flex align-items-center justify-content-between flex-fill"
                              >
                              <div class="mt-0">
                                 <h5
                                    class="mb-1 fs-13 fw-normal text-dark"
                                    >
                                    Paid Monthly
                                    Expenses<span
                                       class="fs-13 fw-semibold ms-1"
                                       >Bills &amp;
                                    Loans</span
                                       >
                                 </h5>
                                 <p
                                    class="mb-0 fs-12 text-muted"
                                    >
                                    Payment 6.43am
                                 </p>
                              </div>
                              <span
                                 class="ms-auto fs-14 fw-semibold"
                                 >
                              <span class="float-end"
                                 >-$1,298</span
                                 >
                              </span>
                           </div>
                        </div>
                     </li>
                     <li>
                        <a
                           href="javascript:void(0);"
                           class="stretched-link"
                           ></a>
                        <div
                           class="d-flex align-items-center"
                           >
                           <div class="me-3">
                              <span
                                 class="avatar avatar-rounded bg-primary-transparent"
                                 >
                              <i
                                 class="fe fe-chevrons-right"
                                 ></i>
                              </span>
                           </div>
                           <div
                              class="d-flex align-items-center justify-content-between flex-fill"
                              >
                              <div class="mt-0">
                                 <h5
                                    class="mb-1 fs-13 fw-normal text-dark"
                                    >
                                    To Bel Bcron
                                    Bank<span
                                       class="fs-13 fw-semibold ms-1"
                                       >Savings
                                    Section</span
                                       >
                                 </h5>
                                 <p
                                    class="mb-0 fs-12 text-muted"
                                    >
                                    Transfer 4.53pm
                                 </p>
                              </div>
                              <span
                                 class="ms-auto fs-14 fw-semibold"
                                 >
                              <span class="float-end"
                                 >-$2,543</span
                                 >
                              </span>
                           </div>
                        </div>
                     </li>
                     <li>
                        <a
                           href="javascript:void(0);"
                           class="stretched-link"
                           ></a>
                        <div
                           class="d-flex align-items-center"
                           >
                           <div class="me-3">
                              <span
                                 class="avatar avatar-rounded bg-warning-transparent"
                                 >
                              <i
                                 class="fe fe-briefcase"
                                 ></i>
                              </span>
                           </div>
                           <div
                              class="d-flex align-items-center justify-content-between flex-fill"
                              >
                              <div class="mt-0">
                                 <h5
                                    class="mb-1 fs-13 fw-normal text-dark"
                                    >
                                    Payment For
                                    <span
                                       class="fs-13 fw-semibold ms-1"
                                       >Day Job</span
                                       >
                                 </h5>
                                 <p
                                    class="mb-0 fs-12 text-muted"
                                    >
                                    Received 2.45pm
                                 </p>
                              </div>
                              <span
                                 class="ms-auto fs-14 fw-semibold"
                                 >
                              <span class="float-end"
                                 >+$32,543</span
                                 >
                              </span>
                           </div>
                        </div>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
      <!-- End Row-3 -->
      <!--Row-->
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-title">
                     Top Product Sales Overview
                  </h5>
                  <div class="card-options">
                     <a
                        href="javascript:void(0);"
                        class="option-dots"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                        ><i
                        class="fe fe-more-horizontal"
                        ></i
                        ></a>
                     <div
                        class="dropdown-menu dropdown-menu-right"
                        >
                        <a
                           class="dropdown-item"
                           href="javascript:void(0);"
                           >Today</a
                           >
                        <a
                           class="dropdown-item"
                           href="javascript:void(0);"
                           >Last Week</a
                           >
                        <a
                           class="dropdown-item"
                           href="javascript:void(0);"
                           >Last Month</a
                           >
                        <a
                           class="dropdown-item"
                           href="javascript:void(0);"
                           >Last Year</a
                           >
                     </div>
                  </div>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table
                        class="table mb-0 table-vcenter text-nowrap table-bordered border-top"
                        >
                        <thead class="">
                           <tr>
                              <th>TRACKING ID</th>
                              <th>Product</th>
                              <th>Sold</th>
                              <th>Payment Mode</th>
                              <th>Stock</th>
                              <th>Amount</th>
                              <th>Stock Status</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td>
                                 <span
                                    class="text-primary fw-semibold"
                                    >#CHDE12300</span
                                    >
                              </td>
                              <td class="fw-bold">
                                 <img
                                    class="rounded shadow sales-overview-product-image me-3"
                                    src="../assets/images/orders/7.jpg"
                                    alt="media1"
                                    />
                                 New Book
                              </td>
                              <td>
                                 <span
                                    class="badge bg-primary-transparent"
                                    >181</span
                                    >
                              </td>
                              <td>
                                 <span
                                    class="fw-semibold"
                                    >
                                 Online Payment</span
                                    >
                              </td>
                              <td>112</td>
                              <td class="number-font">
                                 $2,356
                              </td>
                              <td>
                                 <i
                                    class="align-middle ri-check-line d-inline-flex fw-bold fs-16 me-1 text-success"
                                    ></i>
                                 In Stock
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <span
                                    class="text-primary fw-semibold"
                                    >#CHDE12301</span
                                    >
                              </td>
                              <td class="fw-bold">
                                 <img
                                    class="rounded shadow sales-overview-product-image me-3"
                                    src="../assets/images/orders/8.jpg"
                                    alt="media1"
                                    />
                                 New Bowl
                              </td>
                              <td>
                                 <span
                                    class="badge bg-info-transparent"
                                    >110</span
                                    >
                              </td>
                              <td>
                                 <span
                                    class="fw-semibold"
                                    >Cash on
                                 Delivery</span
                                    >
                              </td>
                              <td>210</td>
                              <td class="number-font">
                                 $3,522
                              </td>
                              <td>
                                 <i
                                    class="align-middle ri-check-line d-inline-flex fw-bold fs-16 text-success"
                                    ></i>
                                 In Stock
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <span
                                    class="text-primary fw-semibold"
                                    >#CHDE12302</span
                                    >
                              </td>
                              <td class="fw-bold">
                                 <img
                                    class="rounded shadow sales-overview-product-image me-3"
                                    src="../assets/images/orders/9.jpg"
                                    alt="media1"
                                    />
                                 Modal Car
                              </td>
                              <td>
                                 <span
                                    class="badge bg-success-transparent"
                                    >153</span
                                    >
                              </td>
                              <td>
                                 <span
                                    class="fw-semibold"
                                    >Cash on
                                 Delivery</span
                                    >
                              </td>
                              <td>215</td>
                              <td class="number-font">
                                 $5,362
                              </td>
                              <td>
                                 <i
                                    class="align-middle ri-check-line d-inline-flex fw-bold fs-16 text-success"
                                    ></i>
                                 In Stock
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <span
                                    class="text-primary fw-semibold"
                                    >#CHDE12303</span
                                    >
                              </td>
                              <td class="fw-bold">
                                 <img
                                    class="rounded shadow sales-overview-product-image me-3"
                                    src="../assets/images/orders/10.jpg"
                                    alt="media1"
                                    />
                                 Headset
                              </td>
                              <td>
                                 <span
                                    class="badge bg-primary-transparent"
                                    >221</span
                                    >
                              </td>
                              <td>
                                 <span
                                    class="fw-semibold"
                                    >Online
                                 Payment</span
                                    >
                              </td>
                              <td>102</td>
                              <td class="number-font">
                                 $1,326
                              </td>
                              <td>
                                 <i
                                    class="align-middle ri-check-line d-inline-flex fw-bold fs-16 text-success"
                                    ></i>
                                 In Stock
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <span
                                    class="text-primary fw-semibold"
                                    >#CHDE12304</span
                                    >
                              </td>
                              <td class="fw-bold">
                                 <img
                                    class="rounded shadow sales-overview-product-image me-3"
                                    src="../assets/images/orders/12.jpg"
                                    alt="media1"
                                    />
                                 Watch
                              </td>
                              <td>
                                 <span
                                    class="badge bg-danger-transparent"
                                    >314</span
                                    >
                              </td>
                              <td>
                                 <span
                                    class="fw-semibold"
                                    >Cash on
                                 Delivery</span
                                    >
                              </td>
                              <td>325</td>
                              <td class="number-font">
                                 $5,234
                              </td>
                              <td>
                                 <i
                                    class="align-middle ri-check-line d-inline-flex fw-bold fs-16 text-success"
                                    ></i>
                                 In Stock
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <span
                                    class="text-primary fw-semibold"
                                    >#CHDE12305</span
                                    >
                              </td>
                              <td class="fw-bold">
                                 <img
                                    class="rounded shadow sales-overview-product-image me-3"
                                    src="../assets/images/orders/13.jpg"
                                    alt="media1"
                                    />
                                 Shoes
                              </td>
                              <td>
                                 <span
                                    class="badge bg-success-transparent"
                                    >181</span
                                    >
                              </td>
                              <td>
                                 <span
                                    class="fw-semibold"
                                    >Online
                                 Payment</span
                                    >
                              </td>
                              <td>0</td>
                              <td class="number-font">
                                 $3,256
                              </td>
                              <td>
                                 <i
                                    class="align-middle ri-alert-fill d-inline-flex fs-16 text-warning"
                                    ></i>
                                 Out of stock
                              </td>
                           </tr>
                           <tr class="mb-0">
                              <td>
                                 <span
                                    class="text-primary fw-semibold"
                                    >#CHDE12306</span
                                    >
                              </td>
                              <td class="fw-bold">
                                 <img
                                    class="rounded shadow sales-overview-product-image me-3"
                                    src="../assets/images/orders/11.jpg"
                                    alt="media1"
                                    />EarPhones
                              </td>
                              <td>
                                 <span
                                    class="badge bg-warning-transparent"
                                    >680</span
                                    >
                              </td>
                              <td>
                                 <span
                                    class="fw-semibold"
                                    >Online
                                 Payment</span
                                    >
                              </td>
                              <td>0</td>
                              <td class="number-font">
                                 $7,652
                              </td>
                              <td>
                                 <i
                                    class="align-middle ri-alert-fill d-inline-flex fs-16 text-danger"
                                    ></i>
                                 Out of stock
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!--End row-->
   </div>
</div>

            

@include('admin.layouts.footer')