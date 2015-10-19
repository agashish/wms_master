<div class="rightside bg-grey-100">
    <div class="page-head bg-grey-100">        
        <h1 class="page-title"><?php echo "Order ( Linnworks API ) "; ?></h1>
				<!-- api top menu -->
					<?php echo $this->element('api_top_menu'); ?>
				<!-- api top menu -->
			<div class="panel-title no-radius bg-green-500 color-white no-border">
			</div>
    </div>
    
    <div class="container-fluid">
		<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border ">
                                <div class="panel-title bg-white no-border">									
									<div class="panel-tools">																	
									</div>
								</div>
							   <div class="panel-body no-padding-top bg-white">
								  		
								  		<table class="table table-bordered table-striped dataTable" id="example1" aria-describedby="example1_info">
										<thead class="parentCheck" >
											<tr role="row" class="parentInner" >
												<th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
													Label Id
												</th>
												<th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Brand: activate to sort column descending">Template Type</th>
												<th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Brand: activate to sort column descending">Template Name</th>
											</tr>
										</thead>
										<tbody role="alert" aria-live="polite" aria-relevant="all"> 
											<?php
												foreach( $prints as $index => $value ): 
											?>                                           
                                            <tr class="odd">												
												<td class=" sorting_1 sortingChild" style="width:100px;" >	
													<?php echo $value->pkTemplateRowId; ?>
												</td>
												<td class="  sorting_1"><?php echo $value->TemplateType; ?></td>			
												<td class="  sorting_1"><?php echo $value->TemplateName; ?></td>			
											</tr>		
											<?php
												endforeach;
											?>	
										    
                                      </tbody>
									</table>		
								</div>
                            </div>
                        </div>
                    </div>
				<!-- BEGIN FOOTER -->
					<?php echo $this->element('footer'); ?>
				<!-- END FOOTER -->
            </div>
    </div>
</div>
