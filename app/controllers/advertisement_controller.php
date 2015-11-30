<?php
class AdvertisementController extends AppController
{
    public function index()
    {
        $user = $this->start();
        $advertisement_service = $user->getServiceLocator()->getAdvertisementService();
        $is_following_only = Param::get('is_following_only', true);
        $sort_type = Param::get('sort_type', AdvertisementService::SORT_TYPE_TRENDING);
        $advertisements = $advertisement_service->getAll($is_following_only);
        $advertisement_service->sortAdvertisements($advertisements, $sort_type);
        $this->set(get_defined_vars());
    }

    public function view()
    {
        $user = $this->start();
        $advertisement_id = Param::get('advertisement_id');
        $advertisement = $user->getServiceLocator()->getAdvertisementService()->getByAdvertisementId($advertisement_id);
        $this->set(get_defined_vars());
    }

    public function update_feedback()
    {
        $user = $this->start();
        $advertisement_id = Param::get('advertisement_id');
        $is_liked = Param::get('is_liked');
        $user->getServiceLocator()->getAdvertisementService()->updateFeedback($advertisement_id, $is_liked);
        $this->set(get_defined_vars());
    }
}