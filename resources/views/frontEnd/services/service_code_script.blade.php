<script>
    $(document).ready(function(){
        let selected_ids = JSON.parse('<?php echo json_encode($selected_ids)?>');
        let ids = []
        let selected_codes = JSON.parse('<?php echo(isset($service_codes) ? json_encode($service_codes) : json_encode([])) ?>');
        if(selected_ids.length > 0){
            for (let index = 0; index < selected_ids.length; index++) {
                ids.push(selected_ids[index])
            }
        }
        let service_recordid = "{{ isset($service) ? $service->service_recordid  : ''}}"
        if(ids.length > 0){
            saveIds(ids)
        }
        $('.code_category_ids').change(function(){
            let value = $(this).val()
            if(!$(this).prop('checked')){
                if(confirm('Deselecting this category will clear codes for this category from this services.')){
                    $(this).removeAttr('checked');
                    ids.splice(ids.indexOf(value),1)
                    saveIds(ids)
                }else{
                    $(this).prop('checked',true);
                }
            }else{
                ids.push(value)
                saveIds(ids)
            }
        })
        function saveIds(ids){
            $.ajax({
                method: 'post',
                headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                url : '{{ route("services.add_code_category_ids") }}',
                data:{ids,service_recordid,selected_codes},
                success:function(resp){
                    if(resp){
                        $('#accordion-sdoh-codes').empty()
                        $('#accordion-sdoh-codes').html(resp)
                        $('.selectpicker').selectpicker('refresh')
                    }
                },
                error:function(error){
                    console.log(error)
                }
            })
        }
        $(document).on('change','select[name="code_conditions[]"]', function(){
            let code_conditions = $(this).val()
            let id = $(this).data('id')
            let exist_codes = selected_codes.find((el) => el.includes('_'+id))
            if(exist_codes){
                selected_codes.splice(selected_codes.indexOf(exist_codes),1)
            }
            if(code_conditions){
                selected_codes.push(code_conditions)
            }
        })
        $(document).on('change','select[name="goal_conditions[]"]', function(){
            let goal_conditions = $(this).val()
            let id = $(this).data('id')
            let exist_codes = selected_codes.find((el) => el.includes('_'+id))
            if(exist_codes){
                selected_codes.splice(selected_codes.indexOf(exist_codes),1)
            }
            if(goal_conditions){
                selected_codes.push(goal_conditions)
            }
        })
        $(document).on('change','input[name="activities_conditions[]"]', function(){
            let activities_conditions = $(this).val()
            let id = $(this).data('id')
            let exist_codes = selected_codes.find((el) => el.includes('_'+id))
            if(exist_codes){
                selected_codes.splice(selected_codes.indexOf(exist_codes),1)
            }
            if(activities_conditions && $(this).prop('checked')){
                selected_codes.push('3_'+activities_conditions)
            }
        })
    })
</script>
