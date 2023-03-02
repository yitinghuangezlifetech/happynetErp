<?php

namespace App\Http\Controllers;

use DB;
use App\Models\TopicType;
use App\Models\QuestionnaireEvent;
use App\Models\Questionnaire;
use App\Models\QuestionnaireTopic;
use App\Models\QuestionnaireTopicOption;
use Illuminate\Http\Request;

class QuestionnaireController extends BasicController {

    public function create(Request $request) {
        $types = app(TopicType::class)->get();

        return view('questionnaires.create', compact(
            'types'
        ));
    }

    public function preview(Request $request) {
        $event = app(QuestionnaireEvent::class)->find($request->questionnaire_event_id);

        return view('questionnaires.preview', compact(
            'event', 'request'
        ));
    }

    public function store(Request $request) {
        if ($request->user()->super_admin != 1) {
            if ($request->user()->cannot('create_'.$this->slug,  $this->model)) {
                return view('alerts.error', [
                    'msg' => '您的權限不足, 請洽管理人員開通權限',
                    'redirectURL' => '/'
                ]);
            }
        }

        $validator = $this->createRule($request);

        if (!is_array($validator) && $validator->fails()){
            return view('alerts.error',[
                'msg'=>$validator->errors()->all()[0],
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }

        DB::beginTransaction();

        try {
            $formData = $request->except('_token', 'topics');
            $formData['id'] = uniqid();

            $questionnaire = app(Questionnaire::class)->create($formData);
            $topics = $request->topics;

            DB::commit();

            $this->proccessTopics($questionnaire, $topics);
        
            return view('alerts.success', [
                'msg'=>'資料新增成功',
                'redirectURL'=>route($this->slug.'.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return view('alerts.error',[
                'msg'=>$e->getMessage(),
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }
    }

    public function edit(Request $request, $id) {
        if ($request->user()->super_admin != 1) {
            if ($request->user()->cannot('edit_'.$this->slug,  $this->model)) {
                return view('alerts.error', [
                    'msg' => '您的權限不足, 請洽管理人員開通權限',
                    'redirectURL' => '/'
                ]);
            }
        }

        $types = app(TopicType::class)->get();

        $data = app(Questionnaire::class)->find($id);

        if (!$data) {
            return view('alerts.error',[
                'msg'=>'資料不存在',
                'redirectURL'=>route($this->slug.'.index')
            ]); 
        }

        if(view()->exists($this->slug.'.edit')) {
            $this->editView = $this->slug.'.edit';
        }

        return view($this->editView, [
            'data'=>$data,
            'id'=>$id,
            'types'=>$types
        ]);
    }

    public function update(Request $request, $id) {
        if ($request->user()->super_admin != 1) {
            if ($request->user()->cannot('update_'.$this->slug,  $this->model)) {
                return view('alerts.error', [
                    'msg' => '您的權限不足, 請洽管理人員開通權限',
                    'redirectURL' => '/'
                ]);
            }
        }

        $validator = $this->createRule($request);

        if (!is_array($validator) && $validator->fails()){
            return view('alerts.error',[
                'msg'=>$validator->errors()->all()[0],
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }

        DB::beginTransaction();

        try {
            $formData = $request->except('_token', 'topics', '_method');

            app(Questionnaire::class)->where('id', $id)->update($formData);
            DB::commit();

            $questionnaire = app(Questionnaire::class)->where('id', $id)->first();
            $topics = $request->topics;

            $this->proccessTopics($questionnaire, $topics);
        
            return view('alerts.success', [
                'msg'=>'資料更新成功',
                'redirectURL'=>route($this->slug.'.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return view('alerts.error',[
                'msg'=>$e->getMessage(),
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }
    }

    private function proccessTopics($questionnaire, $topics=null) {
        if (!empty($topics) && count($topics) > 0) {
            app(QuestionnaireTopic::class)->where('questionnaire_id', $questionnaire->id)->delete();
            app(QuestionnaireTopicOption::class)->where('questionnaire_id', $questionnaire->id)->delete();

            foreach ($topics as $topic) {
                $data = app(QuestionnaireTopic::class)->create([
                    'id' => uniqid(),
                    'questionnaire_id' => $questionnaire->id,
                    'topic_type' => $topic['topic_type'],
                    'subject' => $topic['subject'],
                    'sort' => $topic['sort']
                ]);
                if (isset($topic['items']) && count($topic['items']) > 0) {
                    foreach ($topic['items'] as $item) {
                        app(QuestionnaireTopicOption::class)->create([
                            'id' => uniqid(),
                            'questionnaire_id' => $questionnaire->id,
                            'questionnaire_topic_id' => $data->id,
                            'name' => $item['name']
                        ]);
                    }
                }
            }
        }
    }
}
