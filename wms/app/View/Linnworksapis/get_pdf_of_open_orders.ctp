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
													Order Id's
												</th>
												<th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Brand: activate to sort column descending">Pdf Link</th>												
											</tr>
										</thead>
										<tbody role="alert" aria-live="polite" aria-relevant="all"> 											                                           
                                            <tr class="odd">												
												<td class=" sorting_1 sortingChild" >
												<?php
													foreach( $printPdfLink->IdsProcessed as $index => $value ):
												?>
													<?php print $value; ?>
													<?php print "</br>"; ?>
												<?php
													endforeach;
												?>	
												</td>												
												<td class="  sorting_1">													
													<a href="<?php echo $printPdfLink->URL; ?>" target="_blank">
														<?php
															echo $this->Form->button(
																'Pdf( Print Labels ) Download', 
																array(
																	'formaction' => '',
																	'escape' => true,
																	'class'=>'btn bg-orange-500 color-white btn-dark margin-right-10 padding-left-40 padding-right-40'	
																)
															);	
														?>
													</a>													
												</td>
											</tr>													
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
