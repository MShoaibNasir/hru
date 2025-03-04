@if(!in_array($section->id, [144, 174]))
@endif

@if(!in_array($section->id, [174, 175, 176, 146159, 146160, 146161, 146169, 146170]))
<div id="section_{{ $section->id }}" class="order_{{ $section->section_order }} data-view">
    <div class="accordion-header" onclick="toggleAccordion(this)">{{ $section->name }}</div>
    <div class="accordion-content">
      <x-backend.construction.sectiondefault :surveyformid="$constructionformid" :stage="$stage" :sectionid="$section->id" :questions="$section->questions"/>
    </div>
</div>
@endif

<?php /*
Stage 1
Section 144
if No 2550 Get Q ANS LIST 2774
if No 2552 Get Q ANS LIST 2775        
if No 2554 Get Q ANS LIST 2577



Stage 2
Section 146158
if No 277396 Get Q ANS LIST 277397
if No 277398 Get Q ANS LIST 277399        
if No 277400 Get Q ANS LIST 277401



Stage 3
Section 131
if No 277407 Get Q ANS LIST 277408
if No 277409 Get Q ANS LIST 277410        
if No 277411 Get Q ANS LIST 277412
*/ ?>